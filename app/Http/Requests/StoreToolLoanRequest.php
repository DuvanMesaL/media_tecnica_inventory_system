<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tool;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;


class StoreToolLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'technical_program_id' => 'required|exists:technical_programs,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'loan_date' => 'required|date|after_or_equal:today',
            'expected_return_date' => 'required|date|after:loan_date',
            'notes' => 'nullable|string|max:1000',
            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.quantity' => 'required|integer|min:1'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate classroom belongs to technical program
            if ($this->filled(['technical_program_id', 'classroom_id'])) {
                $classroom = Classroom::find($this->classroom_id);
                if ($classroom && $classroom->technical_program_id != $this->technical_program_id) {
                    $validator->errors()->add('classroom_id', 'Selected classroom does not belong to the selected technical program.');
                }
            }

            // Validate tool availability
            if ($this->filled('tools')) {
                foreach ($this->tools as $index => $toolData) {
                    if (isset($toolData['tool_id']) && isset($toolData['quantity'])) {
                        $tool = Tool::find($toolData['tool_id']);
                        if ($tool && !$tool->isAvailable($toolData['quantity'])) {
                            $validator->errors()->add(
                                "tools.{$index}.quantity",
                                "Insufficient quantity for {$tool->name}. Available: {$tool->available_quantity}"
                            );
                        }
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'technical_program_id.required' => 'Please select a technical program.',
            'classroom_id.required' => 'Please select a classroom.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'loan_date.required' => 'Please specify the loan date.',
            'loan_date.after_or_equal' => 'Loan date cannot be in the past.',
            'expected_return_date.required' => 'Please specify the expected return date.',
            'expected_return_date.after' => 'Return date must be after the loan date.',
            'tools.required' => 'Please select at least one tool.',
            'tools.min' => 'Please select at least one tool.',
            'tools.*.tool_id.required' => 'Please select a tool.',
            'tools.*.tool_id.exists' => 'Selected tool does not exist.',
            'tools.*.quantity.required' => 'Please specify the quantity.',
            'tools.*.quantity.min' => 'Quantity must be at least 1.'
        ];
    }
}

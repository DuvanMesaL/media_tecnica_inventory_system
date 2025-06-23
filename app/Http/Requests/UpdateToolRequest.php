<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage tools');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tools')->ignore($this->tool->id)
            ],
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
            'condition' => 'required|in:good,damaged,lost',
            'total_quantity' => 'required|integer|min:1|max:9999',
            'warehouse_id' => 'required|exists:warehouses,id',
            'unit_price' => 'nullable|numeric|min:0|max:999999.99'
        ];
    }
}

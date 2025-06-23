<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermissionTo('manage tools');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:tools,code|max:50',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|max:255',
            'condition' => 'required|in:good,damaged,lost',
            'total_quantity' => 'required|integer|min:1|max:9999',
            'warehouse_id' => 'required|exists:warehouses,id',
            'unit_price' => 'nullable|numeric|min:0|max:999999.99'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The tool name is required.',
            'code.required' => 'The tool code is required.',
            'code.unique' => 'This tool code already exists.',
            'category.required' => 'Please select a category.',
            'condition.required' => 'Please select the tool condition.',
            'condition.in' => 'Invalid condition selected.',
            'total_quantity.required' => 'Please specify the total quantity.',
            'total_quantity.min' => 'Quantity must be at least 1.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse does not exist.',
            'unit_price.numeric' => 'Unit price must be a valid number.',
            'unit_price.min' => 'Unit price cannot be negative.'
        ];
    }
}

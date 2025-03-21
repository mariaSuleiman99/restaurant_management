<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;


class OrderItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'count' => 'required|integer|min:1', // Count must be a positive integer
            'price' => 'required|numeric|min:0', // Price must be a positive number
            'item_id' => 'required|exists:items,id', // Must reference an existing item
            'order_id' => 'nullable|exists:orders,id', // Must reference an existing order
        ];
    }

    /**
     * Custom error messages (optional).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'count.required' => 'The count is required.',
            'count.integer' => 'The count must be an integer.',
            'count.min' => 'The count must be at least 1.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a numeric value.',
            'price.min' => 'The price must be at least 0.',
            'item_id.required' => 'The item ID is required.',
            'item_id.exists' => 'The selected item ID is invalid.',
            'order_id.exists' => 'The selected order ID is invalid.',
        ];
    }
}

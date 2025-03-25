<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'count' => 'sometimes|integer|min:1', // Only validate if present
            'price' => 'sometimes|numeric|min:0', // Only validate if present
            'item_id' => 'sometimes|exists:items,id', // Only validate if present
            'order_id' => 'sometimes|exists:orders,id', // Only validate if present
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
            'count.integer' => 'The count must be an integer.',
            'count.min' => 'The count must be at least 1.',
            'price.numeric' => 'The price must be a numeric value.',
            'price.min' => 'The price must be at least 0.',
            'item_id.exists' => 'The selected item ID is invalid.',
            'order_id.exists' => 'The selected order ID is invalid.',
        ];
    }
}

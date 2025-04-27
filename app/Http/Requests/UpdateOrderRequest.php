<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id', // Only validate if present
            'total_price' => 'sometimes|numeric|min:0', // Only validate if present
            'count' => 'sometimes|integer|min:1', // Only validate if present
            'status' => 'sometimes|in:InDelivery,InProcess,Pending,InCart', // Only validate if present
            'order_items' => 'required|array', // Ensures "items" is an array
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
            'user_id.exists' => 'The selected user ID is invalid.',
            'total_price.numeric' => 'The total price must be a numeric value.',
            'total_price.min' => 'The total price must be at least 0.',
            'count.integer' => 'The count must be an integer.',
            'count.min' => 'The count must be at least 1.',
            'status.in' => 'The status must be one of: InDelivery, InProcess, Pending, InCart.',
        ];
    }
}

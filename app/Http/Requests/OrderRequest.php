<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // Must reference an existing user
            'total_price' => 'required|numeric|min:0', // Total price must be a positive number
            'count' => 'required|integer|min:1', // Count must be a positive integer
            'status' => 'required|in:InDelivery,InProcess,Pending,InCart', // Status must be one of the defined enums
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
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'total_price.required' => 'The total price is required.',
            'total_price.numeric' => 'The total price must be a numeric value.',
            'total_price.min' => 'The total price must be at least 0.',
            'count.required' => 'The count is required.',
            'count.integer' => 'The count must be an integer.',
            'count.min' => 'The count must be at least 1.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be one of: InDelivery, InProcess, Pending, InCart.',
        ];
    }
}

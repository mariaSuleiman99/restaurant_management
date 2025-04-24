<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can customize this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'status' => 'required|in:InDelivery,InProcess,Pending,InCart', // Status must be one of the defined enums
            'delivery_address' => 'nullable|string|max:255', // Optional delivery address
            'payment_method' => 'nullable|string|max:50', // Optional payment method
            'order_items' => 'required|array|min:1', // At least one order item is required
            'order_items.*.item_id' => 'required|exists:items,id', // Each item ID must exist in the items table
            'order_items.*.count' => 'required|integer|min:1', // Count must be a positive integer
            'order_items.*.price' => 'required|numeric|min:0', // Price must be a positive number
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
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be one of: InDelivery, InProcess, Pending, InCart.',
            'delivery_address.string' => 'The delivery address must be a string.',
            'delivery_address.max' => 'The delivery address must not exceed 255 characters.',
            'payment_method.string' => 'The payment method must be a string.',
            'payment_method.max' => 'The payment method must not exceed 50 characters.',
            'order_items.required' => 'At least one order item is required.',
            'order_items.array' => 'The order items must be an array.',
            'order_items.min' => 'You must provide at least one order item.',
            'order_items.*.item_id.required' => 'Each order item must have an item ID.',
            'order_items.*.item_id.exists' => 'One or more item IDs are invalid.',
            'order_items.*.count.required' => 'The count is required for each order item.',
            'order_items.*.count.integer' => 'The count must be an integer for each order item.',
            'order_items.*.count.min' => 'The count must be at least 1 for each order item.',
            'order_items.*.price.required' => 'The price is required for each order item.',
            'order_items.*.price.numeric' => 'The price must be numeric for each order item.',
            'order_items.*.price.min' => 'The price must be at least 0 for each order item.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Name must be a string with max length 255
            'description' => 'required|string', // Description must be a string
            'image' => 'required|string', // Image URL or path must be a string
            'price' => 'required|numeric|min:0', // Price must be a positive number
            'restaurant_id' => 'required|exists:restaurants,id', // Must reference an existing restaurant
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
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'image.required' => 'The image is required.',
            'image.string' => 'The image must be a string (e.g., URL or path).',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a numeric value.',
            'price.min' => 'The price must be at least 0.',
            'restaurant_id.required' => 'The restaurant ID is required.',
            'restaurant_id.exists' => 'The selected restaurant ID is invalid.',
        ];
    }
}

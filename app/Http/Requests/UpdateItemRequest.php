<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255', // Only validate if present
            'description' => 'sometimes|string', // Only validate if present
            'image' => 'sometimes|string', // Only validate if present
            'price' => 'sometimes|numeric|min:0', // Only validate if present
            'restaurant_id' => 'sometimes|exists:restaurants,id', // Only validate if present
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
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            'description.string' => 'The description must be a string.',
            'image.string' => 'The image must be a string (e.g., URL or path).',
            'price.numeric' => 'The price must be a numeric value.',
            'price.min' => 'The price must be at least 0.',
            'restaurant_id.exists' => 'The selected restaurant ID is invalid.',
        ];
    }
}

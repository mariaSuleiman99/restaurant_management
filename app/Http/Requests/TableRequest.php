<?php

namespace App\Http\Requests;

class TableRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'capacity' => 'required|integer|min:1', // Capacity must be a positive integer
            'number' => 'required|string|unique:tables,number|max:255', // Unique table number
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
            'capacity.required' => 'The capacity is required.',
            'capacity.integer' => 'The capacity must be an integer.',
            'capacity.min' => 'The capacity must be at least 1.',
            'number.required' => 'The table number is required.',
            'number.unique' => 'The table number has already been taken.',
            'number.max' => 'The table number must not exceed 255 characters.',
            'restaurant_id.required' => 'The restaurant ID is required.',
            'restaurant_id.exists' => 'The selected restaurant ID is invalid.',
        ];
    }
}

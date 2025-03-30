<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateTableRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Retrieve the table ID from the route parameter
        $tableId = $this->route('id');
        return [
            'capacity' => 'sometimes|integer|min:1', // Only validate if present
            'number' => [
                'sometimes', // Only validate if present
                'string',
                Rule::unique('tables', 'number')->ignore($tableId),
                'max:255',
            ],
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
            'capacity.integer' => 'The capacity must be an integer.',
            'capacity.min' => 'The capacity must be at least 1.',
            'number.unique' => 'The table number has already been taken.',
            'number.max' => 'The table number must not exceed 255 characters.',
            'restaurant_id.exists' => 'The selected restaurant ID is invalid.',
        ];
    }
}

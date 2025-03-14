<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {        // Retrieve the restaurant ID from the route parameter
        $restaurantId = $this->route('restaurant');

        return [
            'mobile_number' => [
                'sometimes', // Only validate if the field is present in the request
                'string',
                Rule::unique('restaurants', 'mobile_number')->ignore($restaurantId),
                'max:15',
            ],
            'name' => [
                'sometimes', // Only validate if the field is present in the request
                'string',
                Rule::unique('restaurants', 'name')->ignore($restaurantId),
                'max:255',
            ],
            'description' => 'nullable|string', // Optional long text description
            'location' => 'nullable|string|max:255', // Optional location
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
            'mobile_number.unique' => 'The mobile number has already been taken.',
            'mobile_number.max' => 'The mobile number must not exceed 15 characters.',
            'name.unique' => 'The restaurant name has already been taken.',
            'name.max' => 'The restaurant name must not exceed 255 characters.',
            'description.string' => 'The description must be a valid string.',
            'location.max' => 'The location must not exceed 255 characters.',
        ];
    }
}

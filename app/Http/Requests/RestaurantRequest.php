<?php

namespace App\Http\Requests;


class RestaurantRequest extends BaseRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile_number' => 'required|string|unique:restaurants,mobile_number|max:15', // Unique mobile number
            'name' => 'required|string|unique:restaurants,name|max:255', // Unique restaurant name
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
            'mobile_number.required' => 'The mobile number is required.',
            'mobile_number.unique' => 'The mobile number has already been taken.',
            'mobile_number.max' => 'The mobile number must not exceed 15 characters.',
            'name.required' => 'The restaurant name is required.',
            'name.unique' => 'The restaurant name has already been taken.',
            'name.max' => 'The restaurant name must not exceed 255 characters.',
            'description.string' => 'The description must be a valid string.',
            'location.max' => 'The location must not exceed 255 characters.',
        ];
    }
}

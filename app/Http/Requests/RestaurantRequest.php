<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\ValidationRule;

class RestaurantRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mobile_number' => 'required|string|unique:restaurants,mobile_number|max:15', // Unique mobile number
            'name' => 'required|string|unique:restaurants,name|max:255', // Unique restaurant name
            'email_address' => 'required|email|max:255', // Unique restaurant name
            'description' => 'nullable|string', // Optional long text description
            'location' => 'nullable|string|max:255', // Optional location
            'profile_image' => 'nullable|string', // Max 2MB,
            'cover_image'=> 'nullable|string', // Max 2MB
            'status'=> 'nullable|string', // Max 2MB
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
            // Profile Image Messages
            'profile_image.image' => 'The profile image must be a valid image file.',
            'profile_image.mimes' => 'The profile image must be of type: jpeg, png, jpg, or gif.',
            'profile_image.max' => 'The profile image size must not exceed 2MB.',
            // Cover Image Messages
            'cover_image.image' => 'The cover image must be a valid image file.',
            'cover_image.mimes' => 'The cover image must be of type: jpeg, png, jpg, or gif.',
            'cover_image.max' => 'The cover image size must not exceed 2MB.',
        ];
    }
}

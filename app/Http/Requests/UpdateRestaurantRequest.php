<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Retrieve the restaurant ID from the route parameter
        $restaurantId = $this->route('id'); // Ensure this matches your route parameter name
        Log::info('$restaurantId:', ['id' => $restaurantId]);
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
            'status' => 'string|max:255', // Optional location
//            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
//            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
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

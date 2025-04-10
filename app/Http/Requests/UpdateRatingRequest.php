<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users to update their own ratings
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'rating' => 'nullable|min:1|max:5', // Rating must be between 1 and 5 (optional)
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating cannot exceed 5.',
        ];
    }
}

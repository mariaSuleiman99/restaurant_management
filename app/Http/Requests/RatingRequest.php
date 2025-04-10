<?php

namespace App\Http\Requests;

class RatingRequest extends BaseRequest
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
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'rateable_type' => 'required|string|in:App\Models\Restaurant,App\Models\Item', // Valid entity types
            'rateable_id' => 'required|integer|exists:' . $this->input('rateable_type') . ',id', // Ensure the entity exists
            'rating' => 'required|min:1|max:5', // Rating must be between 1 and 5
        ];
    }

    /**
     * Custom error messages (optional).
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected user does not exist.',
            'rateable_type.in' => 'Invalid entity type. Must be "App\Models\Restaurant" or "App\Models\Item".',
            'rateable_id.exists' => 'The selected entity does not exist.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating cannot exceed 5.',
        ];
    }
}

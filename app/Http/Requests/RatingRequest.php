<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow all authenticated users to add ratings
    }

    public function rules(): array
    {
        $type = $this->route('type'); // Get the rateable_type from the route parameter
        $rateableTypeMap = [
            'restaurant' => 'App\Models\Restaurant',
            'item' => 'App\Models\Item',
        ];
        if (!isset($rateableTypeMap[$type])) {
            abort(400, 'Invalid rateable type.');
        }
        return [
            'rateable_id' => 'required|integer|exists:' . $rateableTypeMap[$type] . ',id', // Use the mapped model class
            'rating' => 'required|numeric|min:1|max:5|regex:/^\d+(\.\d{1})?$/', // Rating must be between 1 and 5 with one decimal place
        ];
    }

    public function messages(): array
    {
        return [
            'rateable_id.exists' => 'The selected entity does not exist.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating cannot exceed 5.',
            'rating.regex' => 'The rating must have at most one decimal place (e.g., 1.5, 2.0).',
        ];
    }
}

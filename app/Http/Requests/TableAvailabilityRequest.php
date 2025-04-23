<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableAvailabilityRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true; // Allow authenticated users to check table availability
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
//            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'people_count' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'The reservation date is required.',
            'start_time.required' => 'The start time is required.',
            'end_time.required' => 'The end time is required.',
            'end_time.after' => 'The end time must be after the start time.',
            'people_count.required' => 'The number of people is required.',
            'people_count.integer' => 'The number of people must be an integer.',
            'people_count.min' => 'The number of people must be at least 1.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date', // Must be a valid date
            'start_time' => 'required|date_format:H:i:s',
            'duration' => 'required|integer|min:1', // Duration must be a positive integer
//            'user_id' => 'required|exists:users,id', // Must reference an existing user
            'table_id' => 'required', 'exists:tables,id',
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
            'date.required' => 'The reservation date is required.',
            'date.date' => 'The reservation date must be a valid date.',
            'start_time.required' => 'The start time is required.',
            'start_time.date_format' => 'The start time must be in the format HH:mm:ss.',
            'duration.required' => 'The duration is required.',
            'duration.integer' => 'The duration must be an integer.',
            'duration.min' => 'The duration must be at least 1 minute.',
//            'user_id.required' => 'The user ID is required.',
//            'user_id.exists' => 'The selected user ID is invalid.',
            'table_id.required' => 'The table ID is required.',
            'table_id.exists' => 'The selected table ID is invalid.',
            'table_id.unique' => 'This table is already reserved for the selected date and time.',
        ];
    }
}

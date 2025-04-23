<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $reservationId = $this->route('reservation');

        return [
            'date' => 'sometimes|date', // Only validate if present
            'duration' => 'sometimes|integer|min:1', // Only validate if present
            'user_id' => 'sometimes|exists:users,id', // Only validate if present
            'status' => 'sometimes|in:Pending,Approved,Rejected', // Only validate if present
            'table_id' => [
                'sometimes', // Only validate if present
                Rule::unique('reservations', 'table_id')
                    ->where(function ($query) {
                        return $query->where('date', $this->input('date'))
                            ->where('duration', $this->input('duration'));
                    })
                    ->ignore($reservationId),
            ],
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
            'date.date' => 'The reservation date must be a valid date.',
            'duration.integer' => 'The duration must be an integer.',
            'duration.min' => 'The duration must be at least 1 minute.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'table_id.exists' => 'The selected table ID is invalid.',
            'table_id.unique' => 'The table is already reserved for the given date and duration.',
        ];
    }
}

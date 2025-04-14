<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeTableStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow authenticated users to change the table status
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:available,reserved', // Only allow valid statuses
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be either "available" or "reserved".',
        ];
    }
}

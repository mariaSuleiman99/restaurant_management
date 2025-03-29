<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Name is required and must be a string with a max length of 255 characters
            'email' => 'required|string|email|max:255|unique:users', // Email must be valid, unique, and have a max length of 255 characters
            'password' => [
                'required',
                'string',
                Password::min(8) // Minimum 8 characters
                ->letters() // At least one letter
//                ->mixedCase() // At least one uppercase and one lowercase letter
                ->numbers() // At least one number
//                ->symbols(), // At least one symbol
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Name Field Messages
            'name.required' => 'The full name is required.',
            'name.string' => 'The full name must be a valid string.',
            'name.max' => 'The full name must not exceed 255 characters.',

            // Email Field Messages
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a valid string.',
            'email.email' => 'The email address must be in a valid format (e.g., example@example.com).',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'This email address is already registered. Please log in.',

            // Password Field Messages
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 8 characters long.',
//            'password.mixedCase' => 'The password must include both uppercase and lowercase letters.',
            'password.letters' => 'The password must include at least one letter.',
            'password.numbers' => 'The password must include at least one number.',
//            'password.symbols' => 'The password must include at least one symbol (e.g., @, #, $, %).',
        ];
    }
}

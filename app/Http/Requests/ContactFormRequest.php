<?php
// app/Http/Requests/ContactFormRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50', 'min:2'],
            'last_name' => ['required', 'string', 'max:50', 'min:2'],
            'email' => ['required', 'email:rfc,dns', 'max:100'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{10,}$/'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'agree' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter your first name.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'last_name.required' => 'Please enter your last name.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'country_code.required' => 'Please select a country code.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Please enter a valid phone number.',
            'message.required' => 'Please enter your message.',
            'message.min' => 'Message must be at least 10 characters.',
            'message.max' => 'Message must not exceed 2000 characters.',
            'agree.required' => 'You must agree to the privacy policy.',
            'agree.accepted' => 'You must agree to the privacy policy.',
        ];
    }

    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'country_code' => 'country code',
        ];
    }

    protected function prepareForValidation()
    {
        // Clean phone number input - remove non-numeric characters except + sign
        if ($this->has('phone')) {
            $this->merge([
                'phone' => preg_replace('/[^\d+]/', '', $this->phone),
            ]);
        }
    }
}
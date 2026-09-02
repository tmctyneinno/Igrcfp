<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CohortApplicationRequest extends FormRequest
{
    /**
     * Anyone can submit an application — no auth required.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules matching the ApplyModal form fields.
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'level' => ['required', 'string', 'max:150'],
            'discipline' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:2000'],
            'cohort' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * Custom messages so front-end error text reads naturally under each field.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'country.required' => 'Please let us know which country you\'re applying from.',
            'level.required' => 'Please select the level you\'re applying for.',
            'cohort.required' => 'Missing cohort reference — please refresh the page and try again.',
        ];
    }
}
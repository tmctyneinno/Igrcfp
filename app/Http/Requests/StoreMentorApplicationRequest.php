<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMentorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'domain' => ['required', 'string', 'max:150'],
            'region' => ['required', 'string', 'max:150'],
            'country' => ['required', 'string', 'max:150'],
            'bio' => ['required', 'string', 'max:2000'],
            'expertise_summary' => ['required', 'string', 'max:2000'],
            'availability_status' => ['required', 'string', 'in:taking,not_taking'],
            'languages' => ['nullable', 'string', 'max:500'],
            'skills' => ['nullable', 'string', 'max:500'],
            'certifications' => ['nullable', 'string', 'max:500'],
            'max_mentees' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }
}

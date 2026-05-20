<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMentorshipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'goals' => ['required', 'string', 'max:2000'],
            'preferred_duration' => ['nullable', 'string', 'max:100'],
            'availability' => ['nullable', 'string', 'max:100'],
            'communication_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

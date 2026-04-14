<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentorshipDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'string', 'in:accepted,declined'],
            'mentor_feedback' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMentorshipUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:milestone,session,note,feedback'],
            'title' => ['nullable', 'string', 'max:150'],
            'content' => ['nullable', 'string', 'max:2000'],
            'scheduled_at' => ['nullable', 'date'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}

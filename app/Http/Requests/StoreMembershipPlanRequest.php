<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tier_id' => ['required', 'exists:membership_tiers,id'],
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:10'],
            'billing_interval' => ['required', 'string', 'max:50'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:60'],
            'benefits' => ['nullable', 'string', 'max:4000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

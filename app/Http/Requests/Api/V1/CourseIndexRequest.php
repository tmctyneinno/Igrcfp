<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'integer', 'exists:course_categories,id'],
            'level' => ['nullable', 'string', 'max:50'],
            'featured' => ['nullable', 'boolean'],
            'popular' => ['nullable', 'boolean'],
            'instructor_id' => ['nullable', 'integer', 'exists:users,id'],
            'price' => ['nullable', Rule::in(['free', 'paid'])],
            'sort' => ['nullable', Rule::in(['latest', 'oldest', 'title_asc', 'title_desc', 'popular', 'featured', 'price_asc', 'price_desc'])],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}

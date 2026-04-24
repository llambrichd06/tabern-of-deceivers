<?php

namespace App\Http\Requests\Leaderboards;

use Illuminate\Foundation\Http\FormRequest;

class IndexPaginatedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {




        return [
            'filterValue' => ['nullable', 'string'],
            'filterField' => ['nullable', 'string'],
            'sortOrder' => ['nullable', 'integer'],
            'sortField' => ['nullable', 'string'],
            'rows' => ['required', 'integer'],
        ];
    }
}

<?php

namespace App\Http\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;

class PlayCardRequest extends FormRequest
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
            'gameId' => ['required', 'exists:games,id'],
            'idCards' => ['required', 'array'],
            'calledRank' => ['nullable', 'min:0'],
        ];
    }
}

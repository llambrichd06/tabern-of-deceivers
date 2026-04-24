<?php

namespace App\Http\Requests\Leaderboards;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaderboardRequest extends FormRequest
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
            'user_id' => ['nullable', 'exists:users,id'],
            'points' => ['nullable', 'min:0'],
            'wins' => ['nullable', 'min:0'],
            'losses' => ['nullable', 'min:0'],
            'matches' => ['nullable', 'min:0'],
        ];
    }
}

<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class TransferOwnership extends FormRequest
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
            'host_id' => ['required', 'exists:users,id'],
            'player_id' => ['nullable', 'exists:users,id'],
            'room_id' => ['required', 'exists:rooms,id'],
        ];
    }
}

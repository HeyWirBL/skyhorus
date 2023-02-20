<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:100'],
            'usuario' => ['string', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'email' => ['email', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'telefono' => ['nullable', 'max:50'],
            'direccion' => ['nullable'],
        ];
    }
}
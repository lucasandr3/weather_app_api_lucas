<?php

namespace App\Http\Requests\Autenticacao;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Você deve preencher o seu nome.',
            'email.required' => 'Você deve preencher o seu e-mail.',
            'email.email' => 'Você deve informar um e-mail valido.',
            'email.unique' => 'Este e-mail já esta em uso',
            'password.required' => 'Você deve preencher a sua senha.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|min:8'
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'O e-mail é obrigatório',
            'email.email'       => 'Você precisa informar um e-mail válido',
            'password.required' => 'A senha é obrigatória',
            'password.min'      => 'A senha precisa ter o mínimo 8 caracteres'
        ];
    }
}

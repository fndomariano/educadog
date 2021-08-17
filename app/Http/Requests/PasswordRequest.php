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
            'email.required'    => 'Você precisa informar o e-mail',
            'email.email'       => 'Você precisa informar um e-mail válido',
            'password.required' => 'Você precisa informar uma senha',
            'password.min'      => 'A sua senha precisa ter no mínimo 8 dígitos'
        ];
    }
}

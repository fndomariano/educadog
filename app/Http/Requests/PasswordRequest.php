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
            'email'    => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required'    => 'Você precisa informar o e-mail',
            'password.required' => 'Você precisa informar uma senha'
        ];
    }
}

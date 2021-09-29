<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'password.required'  => 'A senha é obrigatória',
            'password.min'       => 'A senha precisa ter o mínimo 8 caracteres',
            'password.confirmed' => 'A senha está diferente da confirmação'
        ];
    }
}

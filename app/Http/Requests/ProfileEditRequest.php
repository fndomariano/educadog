<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEditRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required',
            'email' => 'required|email|unique:customer,email,' . $this->id,
            'phone' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'O nome é obrigatório',
            'email.required' => 'O e-mail é obrigatório',
            'email.email'    => 'O endereço de e-mail deve ser válido',
            'email.unique'    => 'O endereço de e-mail já está sendo usado',
            'phone.required' => 'O telefone é obrigatório'
        ];
    }
}

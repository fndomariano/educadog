<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            'phone' => 'required',
            'contract' => 'mimes:pdf|max:5120'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'O nome é obrigatório',
            'email.required' => 'O e-mail é obrigatório',
            'email.email'    => 'O endereço de e-mail deve ser válido',
            'email.unique'    => 'O endereço de e-mail já está sendo usado',
            'phone.required' => 'O telefone é obrigatório',
            'contract.mimes' => 'Extensão do arquivo está inválida',
            'contract.max' => 'O tamanho máximo permitido do arquivo é 5MB'
        ];
    }
}

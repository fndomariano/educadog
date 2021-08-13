<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
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
            'breed' => 'required',
            'customer_id' => 'required',
            'photo' => 'mimes:jpeg,jpg,bmp,png'
        ];
    }

    public function messages()
    {
        return [
            'name.required'  => 'O nome é obrigatório',
            'breed.required' => 'A raça é obrigatória',
            'customer_id.required' => 'O cliente é obrigatório',
            'photo.mimes' => 'Extensão do arquivo está inválida'
        ];
    }
}

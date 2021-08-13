<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'activity_date' => 'required',
            'description' => 'required',
            'pet_id' => 'required',
            'score' => 'required',
            'files.*' => 'mimes:mp4,jpeg,jpg,bmp,png'
        ];
    }

    public function messages()
    {
        return [
            'activity_date.required'  => 'A data é obrigatória',
            'description.required' => 'A descrição é obrigatória',
            'pet_id.required' => 'O pet é obrigatório',
            'score.required' => 'A nota é obrigatória',
            'files.*.mimes' => 'Extensão do arquivo está inválida'
        ];
    }
}

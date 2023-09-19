<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialElectricoFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string' 
            ],
            'carroceria_id' => [
                'required' 
            ],
            'modelo_id' => [
                'required' 
            ],

        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'Agrega el Nombre de la categoria.',  
            'nombre.string' => 'El nombre debe de ser un string.', 
            'carroceria_id.required' => 'Agrega la carroceria.',  
            'modelo_id.required' => 'Agrega el modelo', 
        ];
    }
}

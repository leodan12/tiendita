<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProduccioncarroFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [

            'carroceria_id' => [
                'required'
            ],
            'nombre' => [
                'required',
                'string'
            ], 
            'modelo_id' => [
                'required',
            ],
            'cantidadcarros' => [
                'required',
                'min:1',
            ],
            'todoenviado' => [
                'required',
                'string'
            ],
            'facturado' => [
                'required',
                'string'
            ],
            'descuento' => [
                'required' 
            ],
            'ordencompra' => [
                'string',
                'nullable'
            ],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'Agrega el Nombre.',
            'carroceria_id.required' => 'Selecciona una carroceria.', 
            'modelo_id.required' => 'Seleccione un modelo.',
            'cantidadcarros.required' => 'Ingrese una cantidad.',
            'descuento.required' => 'Ingrese un descuento.',
        ];
    }
}

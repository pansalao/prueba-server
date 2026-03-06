<?php

namespace App\Livewire\Forms\TipoEvaluacion;

use Livewire\Form;
use Illuminate\Validation\Rule;

class CreateTipoEvaluacionForm extends Form
{
    public $nombre = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                // Validación universal permitiendo caracteres especiales
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\\d\\s\\.,\\-\\(\\)\\\"\\\':\\/]+$/u',
                Rule::unique('tipo_evaluacion', 'nombre_tipo_evaluacion')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El tipo de evaluación es requerido.',
            'nombre.string' => 'El tipo de evaluación debe ser texto.',
            'nombre.min' => 'El tipo de evaluación debe tener al menos 3 caracteres.',
            'nombre.regex' => 'El tipo de evaluación contiene caracteres no permitidos.',
            'nombre.unique' => 'Este tipo de evaluación ya ha sido registrado.',
        ];
    }
}

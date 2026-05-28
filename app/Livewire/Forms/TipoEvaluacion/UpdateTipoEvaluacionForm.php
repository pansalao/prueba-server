<?php

namespace App\Livewire\Forms\TipoEvaluacion;

use Livewire\Form;
use Illuminate\Validation\Rule;

class UpdateTipoEvaluacionForm extends Form
{
    public $id_tipo_evaluacion;
    public $nombre = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'not_regex:/^[0-9]+$/',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\\d\\s\\.,\\-\\(\\)\\\"\\\':\\/]+$/u',
                Rule::unique('tipo_evaluacion', 'nombre_tipo_evaluacion')->ignore($this->id_tipo_evaluacion, 'id_tipo_evaluacion')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El tipo de evaluación es requerido.',
            'nombre.string' => 'El tipo de evaluación debe ser texto.',
            'nombre.min' => 'El tipo de evaluación debe tener al menos 3 caracteres.',
            'nombre.not_regex' => 'El tipo de evaluación no puede estar compuesta únicamente por números.',
            'nombre.regex' => 'El tipo de evaluación contiene caracteres no permitidos.',
            'nombre.unique' => 'Este tipo de evaluación ya ha sido registrado.',
        ];
    }

    public function setForm($tipoEvaluacion)
    {
        $this->id_tipo_evaluacion = $tipoEvaluacion->id_tipo_evaluacion;
        $this->nombre = $tipoEvaluacion->nombre_tipo_evaluacion;
    }
}

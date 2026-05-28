<?php

namespace App\Livewire\Forms\TecnicaEvaluacion;

use Livewire\Form;
use Illuminate\Validation\Rule;

class UpdateTecnicaEvaluacionForm extends Form
{
    public $id_tecnica_evaluacion;
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
                Rule::unique('tecnica_evaluacion', 'nombre_tecnica_evaluacion')->ignore($this->id_tecnica_evaluacion, 'id_tecnica_evaluacion')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'La técnica de evaluación es requerida.',
            'nombre.string' => 'La técnica de evaluación debe ser texto.',
            'nombre.min' => 'La técnica de evaluación debe tener al menos 3 caracteres.',
            'nombre.not_regex' => 'La técnica de evaluación no puede estar compuesta únicamente por números.',
            'nombre.regex' => 'La técnica de evaluación contiene caracteres no permitidos.',
            'nombre.unique' => 'Esta técnica de evaluación ya ha sido registrada.',
        ];
    }

    public function setForm($evaluacion)
    {
        $this->id_tecnica_evaluacion = $evaluacion->id_tecnica_evaluacion;
        $this->nombre = $evaluacion->nombre_tecnica_evaluacion;
    }
}

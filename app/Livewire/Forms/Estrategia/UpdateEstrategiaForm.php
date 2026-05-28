<?php

namespace App\Livewire\Forms\Estrategia;

use Livewire\Form;
use Illuminate\Validation\Rule;

class UpdateEstrategiaForm extends Form
{
    public $id_estrategia_pedagogica;
    public $nombre = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'not_regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) {
                    if (count(array_filter(explode(' ', trim($value)))) < 2) {
                        $fail('La estrategia pedagógica debe contener al menos dos palabras.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\\d\\s\\.,\\-\\(\\)\\\"\\\':\\/]+$/u',
                Rule::unique('tecnica_actividad', 'nombre_tecnica_actividad')->ignore($this->id_estrategia_pedagogica, 'id_tecnica_actividad')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'La estrategia pedagógica es requerida.',
            'nombre.string' => 'La estrategia pedagógica debe ser texto.',
            'nombre.min' => 'La estrategia pedagógica debe tener al menos 3 caracteres.',
            'nombre.not_regex' => 'La estrategia pedagógica no puede estar compuesta únicamente por números.',
            'nombre.regex' => 'La estrategia pedagógica contiene caracteres no permitidos.',
            'nombre.unique' => 'Esta estrategia pedagógica ya ha sido registrada.',
        ];
    }

    public function setForm($estrategia)
    {
        $this->id_estrategia_pedagogica = $estrategia->id_tecnica_actividad;
        $this->nombre = $estrategia->nombre_tecnica_actividad;
    }
}

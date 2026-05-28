<?php

namespace App\Livewire\Forms\Recurso;

use Livewire\Form;
use Illuminate\Validation\Rule;

class UpdateRecursoForm extends Form
{
    public $id_recurso;
    public $nombre = '';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                'not_regex:/^[0-9]+$/',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u',
                Rule::unique('recurso', 'nombre_recurso')->ignore($this->id_recurso, 'id_recurso')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El recurso es requerido.',
            'nombre.string' => 'El recurso debe ser texto.',
            'nombre.min' => 'El recurso debe tener al menos 3 caracteres.',
            'nombre.not_regex' => 'El recurso no puede estar compuesto únicamente por números.',
            'nombre.regex' => 'El recurso contiene caracteres no permitidos.',
            'nombre.unique' => 'Este recurso ya ha sido registrado.',
        ];
    }

    public function setRecurso($id)
    {
        $recurso = \App\Models\Recurso::find($id);
        if ($recurso) {
            $this->id_recurso = $recurso->id_recurso;
            $this->nombre = $recurso->nombre_recurso;
        }
    }

    public function setForm($recurso)
    {
        $this->id_recurso = $recurso->id_recurso;
        $this->nombre = $recurso->nombre_recurso;
    }
}

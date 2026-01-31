<?php

namespace App\Livewire\Forms\Tema;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateTemaForm extends Form
{
    public $id_unidad_curricular = '';
    public $titulo_tema = '';
    public $descripcion_tema = '';
    public $unidad_tema = '';

    protected function rules()
    {
        return [
            'id_unidad_curricular' => 'required|exists:unidad_curricular,id_unidad_curricular',
            'titulo_tema' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s0-9\.]+$/u',
                Rule::unique('tema', 'titulo_tema')
            ],
            'descripcion_tema' => 'nullable|string|max:500',
            'unidad_tema' => 'required|in:1,2,3,4',
        ];
    }

    protected function messages()
    {
        return [
            'id_unidad_curricular.required' => 'Debe seleccionar una unidad curricular.',
            'id_unidad_curricular.exists' => 'La unidad curricular seleccionada no es válida.',
            'titulo_tema.required' => 'El título del tema es obligatorio.',
            'titulo_tema.string' => 'El título debe ser texto.',
            'titulo_tema.min' => 'El título debe tener al menos 3 caracteres.',
            'titulo_tema.max' => 'El título no debe exceder los 255 caracteres.',
            'titulo_tema.regex' => 'El título contiene caracteres no permitidos.',
            'titulo_tema.unique' => 'Ya existe un tema con este título.',
            'descripcion_tema.string' => 'La descripción debe ser texto.',
            'descripcion_tema.max' => 'La descripción no debe exceder los 500 caracteres.',
            'unidad_tema.required' => 'Debe seleccionar un corte (unidad de tema).',
            'unidad_tema.in' => 'El corte seleccionado no es válido.',
        ];
    }

    public function values()
    {
        return [
            'id_unidad_curricular' => $this->id_unidad_curricular,
            'titulo_tema' => $this->titulo_tema,
            'descripcion_tema' => $this->descripcion_tema,
            'unidad_tema' => $this->unidad_tema,
        ];
    }
}

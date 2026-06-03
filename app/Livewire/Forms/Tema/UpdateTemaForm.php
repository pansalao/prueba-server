<?php

namespace App\Livewire\Forms\Tema;

use Illuminate\Validation\Rule;
use Livewire\Form;

class UpdateTemaForm extends Form
{
    public $id = '';
    public $id_unidad_curricular = '';
    public $titulo_tema = '';
    public $unidad_tema = '';
    public $objetivos = [];

    protected function rules()
    {
        return [
            'id_unidad_curricular' => 'required|exists:external_db.unidad_curricular,ucu_codigo',
            'titulo_tema' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'not_regex:/^[0-9]+$/',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s0-9\.,\-:\/\(\)\.]+$/u',
                Rule::unique('tema_unidad', 'titulo_tema')->ignore($this->id, 'id_tema_unidad')
            ],
            'unidad_tema' => 'required|in:1,2,3,4',
            'objetivos.*.titulo_objetivo' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'not_regex:/^[0-9]+$/',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s0-9\.,\-:\/\(\)\.]+$/u',
            ],
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
            'titulo_tema.not_regex' => 'El título del tema no puede estar compuesto únicamente por números.',
            'titulo_tema.regex' => 'El título contiene caracteres no permitidos.',
            'titulo_tema.unique' => 'Ya existe un tema con este título.',
            'unidad_tema.required' => 'Debe seleccionar un corte (unidad de tema).',
            'unidad_tema.in' => 'El corte seleccionado no es válido.',
            'objetivos.*.titulo_objetivo.required' => 'El objetivo es obligatorio.',
            'objetivos.*.titulo_objetivo.min' => 'El objetivo debe tener al menos 3 caracteres.',
            'objetivos.*.titulo_objetivo.not_regex' => 'El objetivo no puede estar compuesto únicamente por números.',
            'objetivos.*.titulo_objetivo.regex' => 'El objetivo contiene caracteres no permitidos.',
        ];
    }

    public function setTema($tema, $objetivos = [])
    {
        $this->id = $tema->id;
        $this->id_unidad_curricular = $tema->id_unidad_curricular;
        $this->titulo_tema = $tema->titulo_tema;
        $this->unidad_tema = $tema->unidad_tema;

        // Asegurar que cada objetivo sea un array, no un objeto stdClass
        $this->objetivos = !empty($objetivos)
            ? array_map(fn($o) => (array) $o, $objetivos)
            : [['titulo_objetivo' => '']];
    }

    public function values()
    {
        return [
            'id_unidad_curricular' => $this->id_unidad_curricular,
            'titulo_tema' => $this->titulo_tema,
            'unidad_tema' => $this->unidad_tema,
            'objetivos' => $this->objetivos,
        ];
    }
}

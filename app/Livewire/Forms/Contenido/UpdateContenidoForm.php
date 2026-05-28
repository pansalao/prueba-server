<?php

namespace App\Livewire\Forms\Contenido;

use Livewire\Form;
use Livewire\Attributes\Validate;

class UpdateContenidoForm extends Form
{
    public $id;
    public $id_tema;
    public $titulo_contenido;
    public $id_objetivo = [];

    protected function rules()
    {
        return [
            'id_tema' => 'required|exists:tema_unidad,id_tema_unidad',
            'id_objetivo' => 'required|array|min:1',
            'id_objetivo.*' => 'exists:objetivo,id_objetivo',
            'titulo_contenido' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'not_regex:/^[0-9]+$/',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s0-9\.]+$/u',
                \Illuminate\Validation\Rule::unique('contenido', 'titulo_contenido')->ignore($this->id, 'id_contenido')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'id_tema.required' => 'Debe seleccionar un tema.',
            'id_tema.exists' => 'El tema seleccionado no es válido.',
            'id_objetivo.required' => 'Debe seleccionar al menos un objetivo.',
            'id_objetivo.array' => 'Los objetivos seleccionados no son válidos.',
            'id_objetivo.min' => 'Debe seleccionar al menos un objetivo.',
            'id_objetivo.*.exists' => 'Uno de los objetivos seleccionados no es válido.',
            'titulo_contenido.required' => 'El título del contenido es obligatorio.',
            'titulo_contenido.string' => 'El título debe ser texto.',
            'titulo_contenido.min' => 'El título debe tener al menos 3 caracteres.',
            'titulo_contenido.max' => 'El título no debe exceder los 255 caracteres.',
            'titulo_contenido.not_regex' => 'El título del contenido no puede estar compuesto únicamente por números.',
            'titulo_contenido.regex' => 'El título contiene caracteres no permitidos.',
            'titulo_contenido.unique' => 'Ya existe un contenido con este título.',
        ];
    }

    public function setContenido($contenido)
    {
        $this->id = $contenido->id;
        $this->id_tema = $contenido->id_tema;
        $this->titulo_contenido = $contenido->titulo_contenido;
        // Si el contenido trae objetivos como array de IDs, los seteamos
        $this->id_objetivo = !empty($contenido->id_objetivo) ? (array) $contenido->id_objetivo : [''];
    }

    public function values()
    {
        return [
            'id_objetivo' => $this->id_objetivo,
            'titulo_contenido' => $this->titulo_contenido,
        ];
    }
}

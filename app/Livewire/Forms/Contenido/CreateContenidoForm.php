<?php

namespace App\Livewire\Forms\Contenido;

use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateContenidoForm extends Form
{
    public $id_tema = '';
    public $titulo_contenido = '';
    public $descripcion_contenido = '';

    protected function rules()
    {
        return [
            'id_tema' => 'required|exists:tema,id_tema',
            'titulo_contenido' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\s0-9\.]+$/u', // Letras, números, espacios y puntos
                Rule::unique('contenido', 'titulo_contenido')
            ],
            'descripcion_contenido' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'id_tema.required' => 'Debe seleccionar un tema.',
            'id_tema.exists' => 'El tema seleccionado no es válido.',
            'titulo_contenido.required' => 'El título del contenido es obligatorio.',
            'titulo_contenido.string' => 'El título debe ser texto.',
            'titulo_contenido.min' => 'El título debe tener al menos 3 caracteres.',
            'titulo_contenido.max' => 'El título no debe exceder los 255 caracteres.',
            'titulo_contenido.regex' => 'El título contiene caracteres no permitidos.',
            'titulo_contenido.unique' => 'Ya existe un contenido con este título.',
            'descripcion_contenido.string' => 'La descripción debe ser texto.',
            'descripcion_contenido.max' => 'La descripción no debe exceder los 500 caracteres.',
        ];
    }

    public function values()
    {
        return [
            'id_tema' => $this->id_tema,
            'titulo_contenido' => $this->titulo_contenido,
            'descripcion_contenido' => $this->descripcion_contenido,
        ];
    }
}

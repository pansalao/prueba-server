<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;

class CreateEventoForm extends Form
{
    public $id_color = '';
    public $descripcion_evento = '';
    public $tipo_evento = '1';
    public $is_laborable = false;
    public $is_repetible = false;

    protected function rules()
    {
        return [
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoCreateRepo();
                    if ($repo->existeEventoConDescripcion($value)) {
                        $fail('Ya existe un evento con esta descripción.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => [
                'required',
                'in:1,2,3'
            ],
            'is_laborable' => ['required', 'boolean'],
            'is_repetible' => ['required', 'boolean'],
            'id_color' => [
                'required',
                'exists:color,id_color',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoCreateRepo();
                    if ($repo->existeColor($value)) {
                        $fail('Este color ya está asignado a otro evento activo.');
                    }
                }
            ],
        ];
    }

    protected function messages()
    {
        return [
            'descripcion_evento.required' => 'La descripción es obligatoria.',
            'descripcion_evento.string' => 'La descripción debe ser texto.',
            'descripcion_evento.max' => 'La descripción no debe exceder 100 caracteres.',
            'descripcion_evento.regex' => 'Formato inválido en la descripción.',
            'tipo_evento.required' => 'El tipo de evento es obligatorio.',
            'tipo_evento.in' => 'El tipo de evento no es válido.',
            'id_color.required' => 'El color es obligatorio.',
            'id_color.exists' => 'El color seleccionado no es válido.',
            'is_laborable.boolean' => 'El valor de laborable debe ser booleano.',
            'is_repetible.boolean' => 'El valor de repetible debe ser booleano.',
        ];
    }
}

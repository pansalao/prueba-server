<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;

class CreateEventoForm extends Form
{
    public $id_color = '';
    public $descripcion_evento = '';
    public $tipo_evento = '1';

    protected function rules()
    {
        return [
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $exists = \Illuminate\Support\Facades\DB::table('evento')
                        ->where('nombre_evento', $value)
                        ->where('estatus', '!=', '3')
                        ->exists();
                    if ($exists) {
                        $fail('Ya existe un evento con esta descripción.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => [
                'required',
                'in:1,2,3,4'
            ],
            'id_color' => [
                'required',
                'exists:color,id_color',
                function ($attribute, $value, $fail) {
                    $exists = \Illuminate\Support\Facades\DB::table('evento')
                        ->where('id_color', $value)
                        ->where('estatus', '!=', '3')
                        ->exists();
                    if ($exists) {
                        $fail('Este color ya está asignado a otro evento activo.');
                    }
                }
            ],
        ];
    }

    protected function messages()
    {
        return [
            'id_calendario.integer' => 'El calendario debe ser un número entero.',
            'dia_inicio_evento.required' => 'La fecha de inicio es obligatoria.',
            'dia_inicio_evento.date' => 'La fecha de inicio debe ser válida.',
            'dia_fin_evento.required' => 'La fecha de fin es obligatoria.',
            'dia_fin_evento.date' => 'La fecha de fin debe ser válida.',
            'dia_fin_evento.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'descripcion_evento.required' => 'La descripción es obligatoria.',
            'descripcion_evento.string' => 'La descripción debe ser texto.',
            'descripcion_evento.max' => 'La descripción no debe exceder 100 caracteres.',
            'descripcion_evento.regex' => 'Formato inválido en la descripción.',
            'tipo_evento.required' => 'El tipo de evento es obligatorio.',
            'tipo_evento.in' => 'El tipo de evento no es válido.',
        ];
    }
}

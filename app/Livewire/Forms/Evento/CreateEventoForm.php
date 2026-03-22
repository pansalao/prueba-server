<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;

class CreateEventoForm extends Form
{
    public $id_calendario = '';
    public $dia_inicio_evento = '';
    public $dia_fin_evento = '';
    public $descripcion_evento = '';
    public $tipo_evento = '';

    protected function rules()
    {
        return [
            'id_calendario' => [
                'nullable',
                'integer'
            ],
            'dia_inicio_evento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $ultimoCalendario = \Illuminate\Support\Facades\DB::table('calendario_academico')
                        ->where('estatus', 1)
                        ->orderBy('id_calendario_academico', 'desc')
                        ->first();

                    if (!$ultimoCalendario) {
                        $fail('No existe un calendario académico configurado para realizar la validación.');
                        return;
                    }

                    // Asignamos el ID del calendario automáticamente si está vacío
                    if (empty($this->id_calendario)) {
                        $this->id_calendario = $ultimoCalendario->id_calendario_academico;
                    }

                    if ($value < $ultimoCalendario->dia_inicio_calendario_academico || $value > $ultimoCalendario->dia_fin_calendario_academico) {
                        $fail("La fecha de inicio debe estar dentro del rango del último calendario configurado ({$ultimoCalendario->dia_inicio_calendario_academico} al {$ultimoCalendario->dia_fin_calendario_academico}).");
                    }
                }
            ],
            'dia_fin_evento' => [
                'required',
                'date',
                'after_or_equal:dia_inicio_evento',
                function ($attribute, $value, $fail) {
                    $ultimoCalendario = \Illuminate\Support\Facades\DB::table('calendario_academico')
                        ->where('estatus', 1)
                        ->orderBy('id_calendario_academico', 'desc')
                        ->first();

                    if (!$ultimoCalendario) {
                        $fail('No existe un calendario académico configurado para realizar la validación.');
                        return;
                    }

                    if ($value < $ultimoCalendario->dia_inicio_calendario_academico || $value > $ultimoCalendario->dia_fin_calendario_academico) {
                        $fail("La fecha de fin debe estar dentro del rango del último calendario configurado ({$ultimoCalendario->dia_inicio_calendario_academico} al {$ultimoCalendario->dia_fin_calendario_academico}).");
                    }
                }
            ],
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoCreateRepo();
                    $id_cal = $this->id_calendario;
                    
                    if (empty($id_cal)) {
                        $ultimo = \Illuminate\Support\Facades\DB::table('calendario_academico')
                            ->where('estatus', 1)
                            ->orderBy('id_calendario_academico', 'desc')
                            ->first();
                        $id_cal = $ultimo ? $ultimo->id_calendario_academico : null;
                    }

                    if ($repo->existeEventoConDescripcion($value, (int) $id_cal)) {
                        $fail('Ya existe un evento con esta descripción en el calendario seleccionado.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => [
                'required',
                'in:1,2,3'
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

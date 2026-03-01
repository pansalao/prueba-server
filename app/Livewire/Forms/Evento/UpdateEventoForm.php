<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;
use Livewire\Attributes\Locked;

class UpdateEventoForm extends Form
{
    #[Locked]
    public $id_evento;

    public $id_lapso;
    public $dia_inicio_evento = '';
    public $dia_fin_evento = '';
    public $semana_evento = '';
    public $descripcion_evento = '';
    public $tipo_evento = '';

    protected function rules()
    {
        return [
            'id_lapso' => [
                'nullable',
                'integer'
            ],
            'dia_inicio_evento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $id_lapso = $this->id_lapso;
                    if (empty($id_lapso)) {
                        $activo = \Illuminate\Support\Facades\DB::connection('pgsql_daece')->table('lapso_academico')->where('lap_estatus', 'A')->where('lap_cerrado', 'N')->first();
                        $id_lapso = $activo ? $activo->lap_codigo : null;
                    }

                    if ($id_lapso) {
                        $lapso = \Illuminate\Support\Facades\DB::connection('pgsql_daece')->table('lapso_academico')->where('lap_codigo', $id_lapso)->first();
                        if ($lapso) {
                            if ($value < $lapso->lap_fecha_inicio || $value > $lapso->lap_fecha_fin) {
                                $fail("La fecha de inicio debe estar entre {$lapso->lap_fecha_inicio} y {$lapso->lap_fecha_fin}.");
                            }
                        }
                    }
                }
            ],
            'dia_fin_evento' => [
                'required',
                'date',
                'after_or_equal:dia_inicio_evento',
                function ($attribute, $value, $fail) {
                    $id_lapso = $this->id_lapso;
                    if (empty($id_lapso)) {
                        $activo = \Illuminate\Support\Facades\DB::connection('pgsql_daece')->table('lapso_academico')->where('lap_estatus', 'A')->where('lap_cerrado', 'N')->first();
                        $id_lapso = $activo ? $activo->lap_codigo : null;
                    }

                    if ($id_lapso) {
                        $lapso = \Illuminate\Support\Facades\DB::connection('pgsql_daece')->table('lapso_academico')->where('lap_codigo', $id_lapso)->first();
                        if ($lapso) {
                            if ($value < $lapso->lap_fecha_inicio || $value > $lapso->lap_fecha_fin) {
                                $fail("La fecha de fin debe estar entre {$lapso->lap_fecha_inicio} y {$lapso->lap_fecha_fin}.");
                            }
                        }
                    }
                }
            ],
            'semana_evento' => [
                'required',
                'integer',
                'min:1'
            ],
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoEditRepo();
                    if ($repo->existeEventoConDescripcion($value, (int) $this->id_lapso, (int) $this->id_evento)) {
                        $fail('Ya existe un evento con esta descripción en el mismo lapso.');
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
            'id_lapso.integer' => 'El lapso debe ser un número entero.',
            'dia_inicio_evento.required' => 'La fecha de inicio es obligatoria.',
            'dia_inicio_evento.date' => 'La fecha de inicio debe ser válida.',
            'dia_fin_evento.required' => 'La fecha de fin es obligatoria.',
            'dia_fin_evento.date' => 'La fecha de fin debe ser válida.',
            'dia_fin_evento.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'semana_evento.required' => 'La semana es obligatoria.',
            'semana_evento.integer' => 'La semana debe ser un número.',
            'semana_evento.min' => 'La semana mínima es 1.',
            'semana_evento.max' => 'La semana máxima es 52.',
            'descripcion_evento.required' => 'La descripción es obligatoria.',
            'descripcion_evento.string' => 'La descripción debe ser texto.',
            'descripcion_evento.max' => 'La descripción no debe exceder 100 caracteres.',
            'descripcion_evento.unique' => 'Ya existe un evento con esta descripción.',
            'descripcion_evento.regex' => 'Formato inválido en la descripción.',
            'tipo_evento.required' => 'El tipo de evento es obligatorio.',
            'tipo_evento.in' => 'El tipo de evento no es válido.',
        ];
    }
}

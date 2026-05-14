<?php

namespace App\Livewire\Forms\Calendario;

use Livewire\Form;
use Illuminate\Support\Facades\DB;

class CreateCalendarioForm extends Form
{
    public $semana_calendario_academico = '';
    public $dia_inicio_calendario_academico = '';
    public $dia_fin_calendario_academico = '';

    // Propiedades para registro rápido de eventos
    public $nombreEventoTemporal = '';
    public $nuevoColorId = '';
    public $nuevoTipo = '1';
    public $nuevoLaborable = false;
    public $nuevoRepetible = false;
    public $idEventoTemporal = null; // Para cuando se edite un evento existente
    public $isCreatingEvento = false; // Controlar si se están aplicando las validaciones de creación rápida

    public function rules()
    {
        $rules = [
            'dia_inicio_calendario_academico' => [
                'required',
                'date',
            ],
            'dia_fin_calendario_academico' => [
                'required',
                'date',
                'after_or_equal:dia_inicio_calendario_academico',
            ],
        ];

        if ($this->isCreatingEvento) {
            $eventRules = [
                'nombreEventoTemporal' => [
                    'required', 'string', 'max:100',
                    function ($attribute, $value, $fail) {
                        $repo = new \App\Repositories\Calendario\CalendarioCreateRepo();
                        if ($repo->existeEventoConNombre($value, $this->idEventoTemporal)) {
                            $fail($this->idEventoTemporal ? 'Ya existe otro evento con esta descripción.' : 'Ya existe un evento con esta descripción.');
                        }
                    },
                    'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
                ],
                'nuevoTipo' => ['required', 'in:1,2,3'],
                'nuevoLaborable' => [
                    'required', 'boolean',
                    function ($attribute, $value, $fail) {
                        if ($this->nuevoTipo == '1' && $value) {
                            $fail('Un feriado nacional no puede ser marcado como laborable.');
                        }
                    }
                ],
                'nuevoRepetible' => [
                    'required', 'boolean',
                    function ($attribute, $value, $fail) {
                        if ($this->nuevoTipo == '1' && $value) {
                            $fail('Un feriado nacional no puede ser marcado como repetible.');
                        }
                    }
                ],
                'nuevoColorId' => [
                    'required', 'exists:color,id_color',
                    function ($attribute, $value, $fail) {
                        $repo = new \App\Repositories\Calendario\CalendarioCreateRepo();
                        if ($repo->existeEventoConColor($value, $this->idEventoTemporal)) {
                            $fail('Este color ya está asignado a otro evento activo.');
                        }
                    }
                ],
            ];
            $rules = array_merge($rules, $eventRules);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'dia_inicio_calendario_academico.required' => 'La fecha de inicio es obligatoria.',
            'dia_inicio_calendario_academico.date' => 'La fecha de inicio debe ser válida.',
            'dia_fin_calendario_academico.required' => 'La fecha de fin es obligatoria.',
            'dia_fin_calendario_academico.date' => 'La fecha de fin debe ser válida.',
            'dia_fin_calendario_academico.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            // Mensajes para registro rápido
            'nombreEventoTemporal.required' => 'La descripción es obligatoria.',
            'nombreEventoTemporal.max' => 'La descripción no debe exceder 100 caracteres.',
            'nombreEventoTemporal.regex' => 'Formato inválido en la descripción.',
            'nuevoTipo.required' => 'El tipo de evento es obligatorio.',
            'nuevoColorId.required' => 'El color es obligatorio.',
        ];
    }

    /**
     * Valida el nombre de un evento y su tipo basándose en las reglas de seguridad y BD.
     */
    public function validarEvento($nombre, $tipo)
    {
        $reglas = [
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑüÜ\d\s\.\,\-\(\)\"\':\/]+$/u'
            ],
            'tipo' => [
                'required',
                'in:1,2,3,4'
            ],
        ];

        $mensajes = [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.regex' => 'El nombre contiene caracteres no permitidos.',
            'tipo.required' => 'El tipo de evento es obligatorio.',
            'tipo.in' => 'El tipo de evento seleccionado no es válido en el sistema.',
        ];

        \Illuminate\Support\Facades\Validator::make(
            ['nombre' => $nombre, 'tipo' => $tipo],
            $reglas,
            $mensajes
        )->validate();
    }

    /**
     * Valida únicamente la sección de fechas del período.
     */
    public function validarSeccionFechas()
    {
        $this->validate([
            'dia_inicio_calendario_academico' => 'required|date',
            'dia_fin_calendario_academico' => 'required|date|after_or_equal:dia_inicio_calendario_academico',
        ]);
    }

    /**
     * Realiza la validación completa del formulario incluyendo la lógica de eventos.
     */
    public function validarFormularioCompleto($eventosRegistrados)
    {
        $errores = [];
        
        // 1. Validar reglas básicas del objeto Form
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errores = array_merge($errores, array_values($e->errors()));
        }

        // 2. Validar que exista al menos un evento
        if (count($eventosRegistrados) === 0) {
            $msg = 'Debe registrar al menos un evento antes de guardar el calendario.';
            $this->addError('eventosRegistrados', $msg);
            $errores[] = [$msg];
        }

        if (count($errores) > 0) {
            // Aplanar array de errores si es necesario
            $todosLosErrores = [];
            foreach ($errores as $err) {
                if (is_array($err)) {
                    foreach ($err as $e) $todosLosErrores[] = $e;
                } else {
                    $todosLosErrores[] = $err;
                }
            }
            return ['valido' => false, 'errores' => $todosLosErrores];
        }

        return ['valido' => true, 'errores' => []];
    }
}

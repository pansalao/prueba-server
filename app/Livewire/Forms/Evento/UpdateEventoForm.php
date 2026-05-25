<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;
use Livewire\Attributes\Locked;

class UpdateEventoForm extends Form
{
    #[Locked]
    public $id_evento = '';

    public $codigo_color_evento = '';
    public $descripcion_evento = '';
    public $tipo_evento = '1';
    public $especial_evento = '';
    public $is_especial = false;
    public $is_laborable = false;
    public $is_repetible = false;
    public $is_rango_dias = false;
    public $rango_dias = '';
    public $is_independiente = true;
    public $is_superponible = true;
    public $is_semana_evento = false;
    public $cantidad_dias_evento = 0;
    public $semanas = [];

    public function setEvento($evento)
    {
        $this->id_evento = $evento->id_evento;
        $this->descripcion_evento = $evento->nombre_evento;
        $this->tipo_evento = $evento->tipo_evento;
        $this->especial_evento = $evento->especial_evento ?? '';
        $this->is_especial = !empty($evento->especial_evento);
        $this->codigo_color_evento = $evento->codigo_color_evento ?? '';
        $this->is_laborable = (bool) $evento->is_laborable_evento;
        $this->is_repetible = (bool) $evento->is_repetible_evento;
        $this->is_rango_dias = (bool) $evento->is_rango_dias_evento;
        $this->rango_dias = $evento->rango_dias_evento;
        $this->is_independiente = (bool) ($evento->is_independiente ?? $evento->is_independiente_evento ?? false);
        $this->is_superponible = (bool) ($evento->is_superponible_evento ?? false);
        $this->is_semana_evento = (bool) ($evento->is_semana_evento ?? false);
        $this->cantidad_dias_evento = $evento->cantidad_dias_evento;
        $this->semanas = is_array($evento->semana_evento) ? $evento->semana_evento : (json_decode($evento->semana_evento, true) ?? []);
    }

    protected function rules()
    {
        return [
            'cantidad_dias_evento' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial && $this->especial_evento == '1') {
                        if (empty($value) && $value !== '0' && $value !== 0) {
                            $fail('La cantidad de días de vacaciones es obligatoria.');
                        } elseif (!is_numeric($value) || $value < 1 || $value > 365) {
                            $fail('La cantidad de días de vacaciones debe ser un número entero entre 1 y 365.');
                        }
                    } else {
                        if ($value !== null && $value !== '' && $value !== 0 && $value !== '0') {
                            $fail('No se permite asignar una cantidad de días de vacaciones para este tipo de evento.');
                        }
                    }
                }
            ],
            'is_independiente' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo_evento, ['1', '2', '6']) && !$value) {
                        $fail('Para los feriados, el evento debe ser obligatoriamente Independiente.');
                    }
                    if ($this->is_especial && !$value) {
                        $fail('Para los eventos especiales, el evento debe ser obligatoriamente Independiente.');
                    }
                }
            ],
            'is_superponible' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo_evento, ['1', '2', '6']) && !$value) {
                        $fail('Para los feriados, el evento debe ser obligatoriamente superponible.');
                    }
                }
            ],
            'descripcion_evento' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoUpdateRepo();
                    if ($repo->existeEventoConDescripcion($value, $this->id_evento)) {
                        $fail('Ya existe otro evento con esta descripción.');
                    }
                },
                'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
            ],
            'tipo_evento' => [
                'required',
                'in:1,2,3,4,5,6',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10']) && $value != '4') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Académico.');
                        } elseif ($this->especial_evento == '1' && $value != '5') {
                            $fail('Para Vacaciones Colectivas, el tipo de evento debe ser obligatoriamente Administrativo/Académico.');
                        } elseif (in_array($this->especial_evento, ['4', '5']) && $value != '6') {
                            $fail('Para Semana Santa y Carnaval, el tipo de evento debe ser Feriado Mundial.');
                        }
                    }
                }
            ],
            'is_especial' => [
                'required',
                'boolean'
            ],
            'especial_evento' => [
                'required_if:is_especial,true',
                'nullable',
                'in:1,2,3,4,5,6,7,8,9,10',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial && !empty($value)) {
                        $exists = \Illuminate\Support\Facades\DB::table('evento')
                            ->where('especial_evento', $value)
                            ->where('id_evento', '!=', $this->id_evento)
                            ->exists();
                        if ($exists) {
                            $fail('Ya existe otro evento registrado con el tipo especial seleccionado.');
                        }
                    }
                }
            ],
            'is_laborable' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10']) && !$value) {
                            $fail('Para este evento especial, debe ser obligatoriamente Laborable.');
                        } elseif (in_array($this->especial_evento, ['1', '4', '5']) && $value) {
                            $fail('Para este evento especial, no debe ser Laborable.');
                        }
                    }
                }
            ],
            'is_repetible' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo_evento, ['3', '4', '5']) && !$value) {
                        if (!($this->is_especial && in_array($this->especial_evento, ['9', '10']))) {
                            $fail('Para este tipo de evento, debe ser obligatoriamente Repetible.');
                        }
                    }
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['1', '2', '3', '7', '8']) && !$value) {
                             $fail('Para este tipo de evento, debe ser obligatoriamente Repetible.');
                        } elseif (in_array($this->especial_evento, ['4', '5', '9', '10']) && $value) {
                             $fail('Para este tipo de evento, debe ser obligatoriamente No Repetible.');
                        }
                    }
                }
            ],
            'codigo_color_evento' => [
                'required',
                'string',
                'size:7',
                'regex:/^#[a-fA-F0-9]{6}$/',
                function ($attribute, $value, $fail) {
                    $repo = new \App\Repositories\Evento\EventoUpdateRepo();
                    if ($repo->existeColor($value, $this->id_evento)) {
                        $fail('Este código de color ya está asignado a otro evento activo.');
                    }
                }
            ],
            'is_rango_dias' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10']) && !$value) {
                            $fail('Para este evento especial, debe tener obligatoriamente cantidad específica de días.');
                        } elseif (in_array($this->especial_evento, ['1', '4', '5']) && $value) {
                            $fail('Para este evento especial, no debe tener cantidad específica de días.');
                        }
                    }
                }
            ],
            'rango_dias' => [
                'required_if:is_rango_dias,true',
                'nullable',
                'integer',
                'min:1',
                'max:90',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10']) && $value != 1) {
                            $fail('Para este evento especial, la cantidad de días debe ser obligatoriamente 1.');
                        } elseif (in_array($this->especial_evento, ['1', '4', '5']) && !empty($value)) {
                            $fail('Para este evento especial, no se debe definir cantidad de días.');
                        }
                    }
                }
            ],
            'semanas' => [
                'required_if:is_semana_evento,true',
                'array',
                function ($attribute, $value, $fail) {
                    if (!$this->is_semana_evento) return;
                    if (empty($value)) {
                        $fail('Debe seleccionar al menos una semana.');
                        return;
                    }
                    if (!$this->is_repetible && count($value) > 1) {
                        $fail('Si el evento no es repetible, solo puede seleccionar una (1) semana.');
                    }
                    
                    $semanasValidas = array_filter($value, function($val) {
                        return $val !== null && $val !== '';
                    });
                    
                    if (count($semanasValidas) !== count(array_unique($semanasValidas))) {
                        $fail('No puede seleccionar la misma semana más de una vez.');
                    }
                    
                    foreach ($value as $semana) {
                        if ($semana !== null && $semana !== '') {
                            if (!is_numeric($semana) || $semana < 1 || $semana > 99) {
                                $fail('Las semanas seleccionadas deben ser un número válido entre 1 y 99.');
                            }
                        }
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
            'especial_evento.required_if' => 'Debe seleccionar qué tipo de evento especial es.',
            'especial_evento.in' => 'El evento especial seleccionado no es válido.',
            'codigo_color_evento.required' => 'El color es obligatorio.',
            'codigo_color_evento.size' => 'El código de color debe tener 7 caracteres (ej: #FF0000).',
            'codigo_color_evento.regex' => 'El formato del código de color debe ser hexadecimal (ej: #FF0000).',
            'is_laborable.boolean' => 'El valor de laborable debe ser booleano.',
            'is_repetible.boolean' => 'El valor de repetible debe ser booleano.',
            'is_superponible.boolean' => 'El valor de superponible debe ser booleano.',
            'is_rango_dias.boolean' => 'El valor de rango de días debe ser booleano.',
            'rango_dias.required_if' => 'La cantidad de días es obligatoria.',
            'rango_dias.integer' => 'La cantidad de días debe ser un número entero.',
            'rango_dias.min' => 'La cantidad de días debe ser al menos 1.',
            'rango_dias.max' => 'La cantidad de días no debe superar los 90 días.',
            'rango_dias.max' => 'La cantidad de días no debe superar los 90 días.',
            'semanas.required_if' => 'Debe seleccionar al menos una semana cuando el evento está asociado a semanas específicas.',
            'semanas.array' => 'Formato inválido de semanas.',
        ];
    }
}

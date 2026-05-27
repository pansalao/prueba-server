<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;

class CreateEventoForm extends Form
{
    public $codigo_color_evento = '#000000';
    public $descripcion_evento = '';
    public $tipo_evento = '1';
    public $especial_evento = '';
    public $is_especial = false;
    public $is_laborable = false;
    public $is_repetible = false;
    public $is_cantidad_dias_evento = false;
    public $is_independiente = true;
    public $is_superponible = true;
    public $is_semana_evento = false;
    public $cantidad_dias_evento = 0;
    public $semanas = [];

    protected function rules()
    {
        return [
            'cantidad_dias_evento' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->is_cantidad_dias_evento) {
                        if ($this->is_especial && in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10'])) {
                            if ($value != 1) {
                                $fail('La cantidad de días debe ser obligatoriamente 1 para este evento especial.');
                            }
                        } elseif ($this->is_especial && in_array($this->especial_evento, ['4', '5'])) {
                            if ($value != 2) {
                                $fail('Para Semana Santa y Carnaval, la cantidad de días debe ser obligatoriamente 2.');
                            }
                        } else {
                            if (empty($value) && $value !== '0' && $value !== 0) {
                                $fail('La cantidad de días es obligatoria.');
                            } elseif (!is_numeric($value) || $value < 1 || $value > 365) {
                                $fail('La cantidad de días debe ser un número entero entre 1 y 365.');
                            }
                        }
                    } else {
                        if ($value !== null && $value !== '' && $value !== 0 && $value !== '0') {
                            $fail('No se permite asignar una cantidad de días si la opción no está habilitada.');
                        }
                    }
                }
            ],
            'is_cantidad_dias_evento' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if ($this->especial_evento == '1') {
                            if (!$value) {
                                $fail('Para las vacaciones, debe habilitarse la cantidad de días obligatoriamente.');
                            }
                        } elseif (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10'])) {
                            if (!$value) {
                                $fail('Este evento especial requiere cantidad de días obligatoriamente (1 día).');
                            }
                        } elseif (in_array($this->especial_evento, ['4', '5'])) {
                            if (!$value) {
                                $fail('Para Semana Santa y Carnaval, debe habilitarse la cantidad de días obligatoriamente.');
                            }
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
                        if (!($this->is_especial && in_array($this->especial_evento, ['4', '5']))) {
                            $fail('Para los feriados, el evento debe ser obligatoriamente superponible.');
                        }
                    }
                }
            ],
            'is_semana_evento' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo_evento, ['1', '2', '6']) && $value) {
                        $fail('Para los feriados, el evento no puede ocurrir en semanas específicas.');
                    }
                }
            ],
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
                'in:1,2,3,4,5,6',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->especial_evento, ['2', '3', '7', '8', '9', '10']) && $value != '4') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Académico.');
                        } elseif ($this->especial_evento == '1' && $value != '5') {
                            $fail('Para Vacaciones Colectivas, el tipo de evento debe ser obligatoriamente Administrativo/Académico.');
                        } elseif (in_array($this->especial_evento, ['4', '5']) && !in_array($value, ['6'])) {
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
                            ->exists();
                        if ($exists) {
                            $fail('Ya existe un evento registrado con el tipo especial seleccionado.');
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
                    $repo = new \App\Repositories\Evento\EventoCreateRepo();
                    if ($repo->existeColor($value)) {
                        $fail('Este código de color ya está asignado a otro evento activo.');
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
                    
                    $semanasNoVacias = array_filter($value, fn($v) => $v !== null && $v !== '');
                    if (count($semanasNoVacias) > 4) {
                        $fail('Un evento puede tener máximo 4 semanas asignadas.');
                    }
                    
                    // Filtrar valores vacíos
                    $semanasValidas = array_filter($value, function($val) {
                        return $val !== null && $val !== '';
                    });
                    
                    // Comprobar duplicados
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
            'is_cantidad_dias_evento.boolean' => 'El valor de cantidad de días debe ser booleano.',
            'semanas.required_if' => 'Debe seleccionar al menos una semana cuando el evento está asociado a semanas específicas.',
            'semanas.array' => 'Formato inválido de semanas.',
            'is_cantidad_dias_evento.required' => 'El campo cantidad de días es obligatorio.',
            'is_independiente.required' => 'El campo independiente es obligatorio.',
            'is_independiente.boolean' => 'El campo independiente debe ser un valor booleano.',
            'is_superponible.required' => 'El campo superponible es obligatorio.',
            'is_semana_evento.required' => 'El campo semana es obligatorio.',
            'is_semana_evento.boolean' => 'El campo semana debe ser un valor booleano.',
            'is_especial.required' => 'El campo especial es obligatorio.',
            'is_especial.boolean' => 'El campo especial debe ser un valor booleano.',
            'is_laborable.required' => 'El campo laborable es obligatorio.',
            'is_repetible.required' => 'El campo repetible es obligatorio.',
        ];
    }
}

<?php

namespace App\Livewire\Forms\Evento;

use Livewire\Form;

class CreateEventoForm extends Form
{
    public $codigo_color_evento = '#000000';
    public $descripcion_evento = '';
    public $tipo_evento = '1';
    public $id_especial_evento = '';
    public $is_especial = false;
    public $is_laborable = false;
    public $is_repetible = false;
    public $is_cantidad_dias_evento = false;
    public $is_independiente = true;
    public $is_superponible = true;
    public $is_semana_evento = false;
    public $is_dia_evento = false;
    public $dia_evento = null;
    public $cantidad_dias_evento = 0;
    public $semanas = [];

    protected function rules()
    {
        return [
            'cantidad_dias_evento' => [
                'exclude_unless:is_cantidad_dias_evento,true',
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->is_cantidad_dias_evento) {
                        if ($this->is_especial && in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '11', '13', '14'])) {
                            if ($value != 1) {
                                $fail('La cantidad de días debe ser obligatoriamente 1 para este evento especial.');
                            }
                        } elseif ($this->is_especial && in_array($this->id_especial_evento, ['4', '5'])) {
                            if ($value != 2) {
                                $fail('Para Semana Santa y Carnaval, la cantidad de días debe ser obligatoriamente 2.');
                            }
                        } elseif ($this->is_especial && $this->id_especial_evento == '1') {
                            if ($value != 60) {
                                $fail('Para Vacaciones Colectivas, la cantidad de días debe ser obligatoriamente 60.');
                            }
                        } else {
                            if (empty($value) && $value !== '0' && $value !== 0) {
                                $fail('La cantidad de días es obligatoria.');
                            } elseif (!is_numeric($value) || $value < 1 || $value > 90) {
                                $fail('La cantidad de días debe ser un número entero entre 1 y 90.');
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
                        if ($this->id_especial_evento == '1') {
                            if (!$value) {
                                $fail('Para las vacaciones, debe habilitarse la cantidad de días obligatoriamente.');
                            }
                        } elseif (in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '11', '13', '14'])) {
                            if (!$value) {
                                $fail('Este evento especial requiere cantidad de días obligatoriamente (1 día).');
                            }
                        } elseif (in_array($this->id_especial_evento, ['4', '5'])) {
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
                    if ($this->is_especial) {
                        if (in_array($this->id_especial_evento, ['7', '8', '13', '14']) && $value) {
                            $fail('Para este evento especial, el evento no puede registrarse fuera de un semestre.');
                        } elseif (!in_array($this->id_especial_evento, ['7', '8', '13', '14']) && !$value) {
                            $fail('Para los demás eventos especiales, el evento debe ser obligatoriamente Independiente.');
                        }
                    }
                }
            ],
            'is_superponible' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if (in_array($this->tipo_evento, ['1', '2', '6']) && !$value) {
                        if (!($this->is_especial && in_array($this->id_especial_evento, ['4', '5']))) {
                            $fail('Para los feriados, el evento debe ser obligatoriamente superponible.');
                        }
                    }
                    if ($this->is_especial && in_array($this->id_especial_evento, ['2', '3', '7', '8', '11', '13', '14']) && $value) {
                        $fail('Para este evento especial, no puede ser superponible.');
                    }
                    if ($this->is_especial && in_array($this->id_especial_evento, ['1', '9', '10']) && !$value) {
                        $fail('Para este evento especial, el evento debe ser obligatoriamente superponible.');
                    }
                }
            ],
            'is_semana_evento' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->is_repetible) {
                        $fail('Si el evento no es repetible, no puede ocurrir en semanas específicas.');
                    }
                    if ($value && $this->is_independiente) {
                        $fail('Un evento que ocurre en semanas específicas no puede registrarse fuera de un semestre (debe depender de un lapso).');
                    }
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
                        if (in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '13', '14']) && $value != '4') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Académico.');
                        } elseif (in_array($this->id_especial_evento, ['1', '11']) && $value != '5') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Administrativo/Académico.');
                        } elseif (in_array($this->id_especial_evento, ['4', '5']) && !in_array($value, ['6'])) {
                            $fail('Para Semana Santa y Carnaval, el tipo de evento debe ser Feriado Mundial.');
                        }
                    }
                }
            ],
            'is_especial' => [
                'required',
                'boolean'
            ],
            'id_especial_evento' => [
                'required_if:is_especial,true',
                'nullable',
                'in:1,2,3,4,5,6,7,8,9,10,11,12,13,14',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial && !empty($value)) {
                        $exists = \Illuminate\Support\Facades\DB::table('evento')
                            ->where('id_especial_evento', $value)
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
                        if (in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '11', '13', '14']) && !$value) {
                            $fail('Para este evento especial, debe ser obligatoriamente Laborable.');
                        } elseif (in_array($this->id_especial_evento, ['1', '4', '5']) && $value) {
                            $fail('Para este evento especial, no debe ser Laborable.');
                        }
                    }
                }
            ],
            'is_repetible' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {

                    if ($this->is_especial) {
                        if (in_array($this->id_especial_evento, ['1', '2', '3', '7', '8', '11', '13', '14']) && !$value) {
                             $fail('Para este tipo de evento, debe ser obligatoriamente Repetible.');
                        } elseif (in_array($this->id_especial_evento, ['4', '5', '9', '10']) && $value) {
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

            'is_dia_evento' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value && !in_array($this->tipo_evento, ['1', '2', '6'])) {
                        $fail('Solo los feriados pueden ocurrir en un día específico.');
                    }
                    if ($value && in_array($this->id_especial_evento, ['4', '5'])) {
                        $fail('Para Carnaval y Semana Santa, esta opción debe ser obligatoriamente falsa.');
                    }
                }
            ],
            'dia_evento' => [
                'exclude_unless:is_dia_evento,true',
                'required_if:is_dia_evento,true',
                'nullable',
                'date'
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
                    
                    // Validar que no haya más de 4 semanas por lapso
                    $semanasLapso1 = array_filter($value, fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 1);
                    $semanasLapso2 = array_filter($value, fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 2);
                    
                    if (count($semanasLapso1) > 4) {
                        $fail('Un evento puede tener máximo 4 semanas en el Lapso 1.');
                    }
                    if (count($semanasLapso2) > 4) {
                        $fail('Un evento puede tener máximo 4 semanas en el Lapso 2.');
                    }
                    
                    // Validar semanas únicas dentro de cada lapso
                    $semanasValidas1 = array_filter($semanasLapso1, fn($s) => !empty($s['semana']) && $s['semana'] !== null && $s['semana'] !== '');
                    $semanasValidas2 = array_filter($semanasLapso2, fn($s) => !empty($s['semana']) && $s['semana'] !== null && $s['semana'] !== '');
                    
                    $nums1 = array_map(fn($s) => (string) $s['semana'], $semanasValidas1);
                    $nums2 = array_map(fn($s) => (string) $s['semana'], $semanasValidas2);
                    
                    if (count($nums1) !== count(array_unique($nums1))) {
                        $fail('No puede seleccionar la misma semana más de una vez en el Lapso 1.');
                    }
                    if (count($nums2) !== count(array_unique($nums2))) {
                        $fail('No puede seleccionar la misma semana más de una vez en el Lapso 2.');
                    }
                }
            ],
            'semanas.*.lapso' => $this->is_semana_evento ? [
                'required',
                'in:1,2'
            ] : [],
            'semanas.*.semana' => $this->is_semana_evento ? [
                'required',
                'numeric',
                'min:1',
                'max:18'
            ] : [],
        ];
    }

    public function validationAttributes()
    {
        $attributes = [];
        $lapso1Count = 1;
        $lapso2Count = 1;

        foreach ($this->semanas as $index => $semana) {
            $lapso = is_array($semana) && isset($semana['lapso']) ? $semana['lapso'] : 1;
            if ($lapso == 1) {
                $attributes["semanas.{$index}.semana"] = "{$lapso1Count} del Lapso 1";
                $attributes["semanas.{$index}.lapso"] = "{$lapso1Count} del Lapso 1";
                $lapso1Count++;
            } else {
                $attributes["semanas.{$index}.semana"] = "{$lapso2Count} del Lapso 2";
                $attributes["semanas.{$index}.lapso"] = "{$lapso2Count} del Lapso 2";
                $lapso2Count++;
            }
        }

        return $attributes;
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
            'id_especial_evento.required_if' => 'Debe seleccionar qué tipo de evento especial es.',
            'id_especial_evento.in' => 'El evento especial seleccionado no es válido.',
            'codigo_color_evento.required' => 'El color es obligatorio.',
            'codigo_color_evento.size' => 'El código de color debe tener 7 caracteres (ej: #FF0000).',
            'codigo_color_evento.regex' => 'El formato del código de color debe ser hexadecimal (ej: #FF0000).',
            'is_laborable.boolean' => 'El valor de laborable debe ser booleano.',
            'is_repetible.boolean' => 'El valor de repetible debe ser booleano.',
            'is_superponible.boolean' => 'El valor de superponible debe ser booleano.',
            'is_cantidad_dias_evento.boolean' => 'El valor de cantidad de días debe ser booleano.',
            'semanas.required_if' => 'Debe seleccionar al menos una semana cuando el evento está asociado a semanas específicas.',
            'semanas.array' => 'Formato inválido de semanas.',
            'semanas.*.semana.required' => 'La semana :attribute no puede estar vacía.',
            'semanas.*.semana.numeric' => 'La semana :attribute debe ser un número.',
            'semanas.*.semana.min' => 'La semana :attribute debe ser mayor o igual a 1.',
            'semanas.*.semana.max' => 'La semana :attribute no debe superar 18.',
            'semanas.*.lapso.required' => 'El lapso de la semana :attribute es obligatorio.',
            'semanas.*.lapso.in' => 'El lapso de la semana :attribute no es válido (debe ser 1 o 2).',
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

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
    public $id_especial_evento = '';
    public $is_especial = false;
    public $is_laborable = false;
    public $is_repetible = false;
    public $is_rango_dias = false;
    public $rango_dias = '';
    public $is_independiente = true;
    public $is_superponible = true;
    public $is_semana_evento = false;
    public $is_dia_evento = false;
    public $dia_evento = null;
    public $cantidad_dias_evento = 0;
    public $semanas = [];

    public function setEvento($evento)
    {
        $this->id_evento = $evento->id_evento;
        $this->descripcion_evento = $evento->nombre_evento;
        $this->tipo_evento = $evento->tipo_evento;
        $this->id_especial_evento = $evento->especial_evento ?? '';
        $this->is_especial = !empty($evento->especial_evento);
        $this->codigo_color_evento = $evento->codigo_color_evento ?? '';
        $this->is_laborable = (bool) $evento->is_laborable_evento;
        $this->is_repetible = (bool) $evento->is_repetible_evento;
        $this->is_rango_dias = (bool) ($evento->is_cantidad_dias_evento ?? false);
        $this->rango_dias = $evento->cantidad_dias_evento;
        $this->is_independiente = (bool) ($evento->is_independiente ?? $evento->is_independiente_evento ?? false);
        $this->is_superponible = (bool) ($evento->is_superponible_evento ?? false);
        $this->is_semana_evento = (bool) ($evento->is_semana_evento ?? false);
        $this->is_dia_evento = (bool) ($evento->is_dia_evento ?? false);
        $this->dia_evento = $evento->dia_evento ? \Carbon\Carbon::parse($evento->dia_evento)->format('Y-m-d') : null;
        $this->cantidad_dias_evento = $evento->cantidad_dias_evento;
        $rawSemanas = is_array($evento->semana_evento) ? $evento->semana_evento : (json_decode($evento->semana_evento, true) ?? []);
        // Convertir formato antiguo (simple array de números) a nuevo formato (objetos con lapso+semana)
        $this->semanas = [];
        foreach ($rawSemanas as $raw) {
            if (is_array($raw) && isset($raw['lapso'])) {
                $this->semanas[] = $raw;
            } elseif (is_numeric($raw) || is_string($raw)) {
                // Formato antiguo: asignar al lapso 1 por defecto
                $this->semanas[] = ['lapso' => 1, 'semana' => (string) $raw];
            }
        }
    }

    protected function rules()
    {
        return [
            'cantidad_dias_evento' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial && $this->id_especial_evento == '1') {
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
                        if (in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '13', '14']) && $value != '4') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Académico.');
                        } elseif (in_array($this->id_especial_evento, ['1', '11']) && $value != '5') {
                            $fail('Para este evento especial, el tipo de evento debe ser obligatoriamente Administrativo/Académico.');
                        } elseif (in_array($this->id_especial_evento, ['4', '5']) && $value != '6') {
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
                        if (in_array($this->id_especial_evento, ['1', '2', '3', '7', '8', '9', '10', '11', '13', '14']) && !$value) {
                            $fail('Para este evento especial, debe tener obligatoriamente cantidad específica de días.');
                        } elseif (in_array($this->id_especial_evento, ['4', '5']) && !$value) {
                            $fail('Para Semana Santa y Carnaval, debe tener obligatoriamente cantidad específica de días.');
                        }
                    }
                }
            ],
            'rango_dias' => [
                'exclude_unless:is_rango_dias,true',
                'required_if:is_rango_dias,true',
                'nullable',
                'integer',
                'min:1',
                'max:90',
                function ($attribute, $value, $fail) {
                    if ($this->is_especial) {
                        if (in_array($this->id_especial_evento, ['2', '3', '7', '8', '9', '10', '11', '13', '14']) && $value != 1) {
                            $fail('Para este evento especial, la cantidad de días debe ser obligatoriamente 1.');
                        } elseif ($this->id_especial_evento == '1' && $value != 60) {
                            $fail('Para Vacaciones Colectivas, la cantidad de días debe ser obligatoriamente 60.');
                        } elseif (in_array($this->id_especial_evento, ['4', '5']) && $value != 2) {
                            $fail('Para Semana Santa y Carnaval, la cantidad de días debe ser obligatoriamente 2.');
                        }
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
            'is_rango_dias.boolean' => 'El valor de rango de días debe ser booleano.',
            'rango_dias.required_if' => 'La cantidad de días es obligatoria.',
            'rango_dias.integer' => 'La cantidad de días debe ser un número entero.',
            'rango_dias.min' => 'La cantidad de días debe ser al menos 1.',
            'rango_dias.max' => 'La cantidad de días no debe superar los 90 días.',
            'semanas.required_if' => 'Debe seleccionar al menos una semana cuando el evento está asociado a semanas específicas.',
            'semanas.array' => 'Formato inválido de semanas.',
            'semanas.*.semana.required' => 'La semana :attribute no puede estar vacía.',
            'semanas.*.semana.numeric' => 'La semana :attribute debe ser un número.',
            'semanas.*.semana.min' => 'La semana :attribute debe ser mayor o igual a 1.',
            'semanas.*.semana.max' => 'La semana :attribute no debe superar 18.',
            'semanas.*.lapso.required' => 'El lapso de la semana :attribute es obligatorio.',
            'semanas.*.lapso.in' => 'El lapso de la semana :attribute no es válido (debe ser 1 o 2).',
            'is_independiente.required' => 'El campo independiente es obligatorio.',
            'is_independiente.boolean' => 'El campo independiente debe ser un valor booleano.',
            'is_superponible.required' => 'El campo superponible es obligatorio.',
            'is_semana_evento.required' => 'El campo semana es obligatorio.',
            'is_semana_evento.boolean' => 'El campo semana debe ser un valor booleano.',
            'is_especial.required' => 'El campo especial es obligatorio.',
            'is_especial.boolean' => 'El campo especial debe ser un valor booleano.',
            'is_laborable.required' => 'El campo laborable es obligatorio.',
            'is_repetible.required' => 'El campo repetible es obligatorio.',
            'is_rango_dias.required' => 'El campo rango de días es obligatorio.',
        ];
    }
}

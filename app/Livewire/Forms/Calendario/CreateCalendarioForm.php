<?php

namespace App\Livewire\Forms\Calendario;

use Livewire\Form;
use Illuminate\Support\Facades\DB;

class CreateCalendarioForm extends Form
{
    public $semana_lapso_uno_calendario_academico = '';
    public $semana_lapso_dos_calendario_academico = '';
    public $semana_lapso_uno_introductorio_calendario_academico = '';
    public $semana_lapso_dos_introductorio_calendario_academico = '';
    public $semana_intensibo_introductorio_calendario_academico = '';
    public $dia_inicio_calendario_academico = '';
    public $dia_fin_calendario_academico = '';

    // Propiedades para registro rápido de eventos
    public $nombreEventoTemporal = '';
    public $nuevoColorHex = '';
    public $nuevoTipo = '1';
    public $nuevoLaborable = false;
    public $nuevoRepetible = false;
    public $nuevoIsRangoDias = false;
    public $nuevoRangoDias = '';

    public $nuevoIsIndependiente = true;
    public $nuevoIsSuperponible = true;
    public $tipo_calendario = '1'; // Nuevo: 1 (Semestral), 2 (Anual)
    public $idEventoTemporal = null; // Para cuando se edite un evento existente
    public $isCreatingEvento = false; // Controlar si se están aplicando las validaciones de creación rápida

    public function rules()
    {
        $rules = [
            'semana_lapso_uno_calendario_academico' => ['required', 'integer', 'min:1', 'max:99'],
            'semana_lapso_dos_calendario_academico' => ['required', 'integer', 'min:1', 'max:99'],
            'semana_lapso_uno_introductorio_calendario_academico' => ['required', 'integer', 'min:0', 'max:99'],
            'semana_lapso_dos_introductorio_calendario_academico' => ['required', 'integer', 'min:0', 'max:99'],
            'semana_intensibo_introductorio_calendario_academico' => ['required', 'integer', 'min:0', 'max:99'],
            'dia_inicio_calendario_academico' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $year = date('Y', strtotime($value));
                    $currentYear = date('Y');
                    if ($year < $currentYear - 2 || $year > $currentYear + 2) {
                        $fail('La fecha de inicio no puede ser más de 2 años anterior ni posterior al año actual (' . $currentYear . ').');
                    }
                }
            ],
            'dia_fin_calendario_academico' => [
                'required',
                'date',
                'after_or_equal:dia_inicio_calendario_academico',
                function ($attribute, $value, $fail) {
                    $year = date('Y', strtotime($value));
                    $currentYear = date('Y');
                    if ($year < $currentYear - 2 || $year > $currentYear + 2) {
                        $fail('La fecha de fin no puede ser más de 2 años anterior ni posterior al año actual (' . $currentYear . ').');
                    }
                }
            ],
        ];

        if ($this->isCreatingEvento) {
            $eventRules = [
                'nombreEventoTemporal' => [
                    'required',
                    'string',
                    'max:100',
                    function ($attribute, $value, $fail) {
                        $repo = new \App\Repositories\Calendario\CalendarioCreateRepo();
                        if ($repo->existeEventoConNombre($value, $this->idEventoTemporal)) {
                            $fail($this->idEventoTemporal ? 'Ya existe otro evento con esta descripción.' : 'Ya existe un evento con esta descripción.');
                        }
                    },
                    'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
                ],
                'nuevoTipo' => ['required', 'in:1,2,3,4,5,6'],
                'nuevoLaborable' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (in_array($this->nuevoTipo, ['1', '2', '6']) && $value) {
                            $fail('Un feriado no puede ser marcado como laborable.');
                        }
                    }
                ],
                'nuevoRepetible' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (in_array($this->nuevoTipo, ['1', '2', '6']) && $value) {
                            $fail('Un feriado no puede ser marcado como repetible.');
                        }
                        if (in_array($this->nuevoTipo, ['3', '4', '5']) && !$value) {
                            $fail('Para este tipo de evento, debe ser obligatoriamente Repetible.');
                        }
                    }
                ],
                'nuevoIsRangoDias' => ['required', 'boolean'],
                'nuevoRangoDias' => [
                    'nullable',
                    function ($attribute, $value, $fail) {
                        if ($this->nuevoIsRangoDias) {
                            if (empty($value) && $value !== '0' && $value !== 0) {
                                $fail('La cantidad de días es obligatoria.');
                            } elseif (!is_numeric($value) || $value < 1 || $value > 365) {
                                $fail('La cantidad de días debe ser un número entero entre 1 y 365.');
                            }
                        } else {
                            if ($value !== null && $value !== '' && $value !== 0 && $value !== '0') {
                                $fail('No se permite asignar una cantidad de días si la opción no está habilitada.');
                            }
                        }
                    }
                ],
                'nuevoIsIndependiente' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (in_array($this->nuevoTipo, ['1', '2', '6']) && !$value) {
                            $fail('Para los feriados, el evento debe ser obligatoriamente Independiente.');
                        }
                    }
                ],
                'nuevoIsSuperponible' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (in_array($this->nuevoTipo, ['1', '2', '6']) && !$value) {
                            $fail('Para los feriados, el evento debe ser obligatoriamente Superponible.');
                        }
                    }
                ],
                'nuevoColorHex' => [
                    'required',
                    'string',
                    'regex:/^#[0-9A-Fa-f]{6}$/',
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
            'semana_lapso_uno_calendario_academico.required' => 'La cantidad de semanas para el lapso 1 es obligatoria.',
            'semana_lapso_uno_calendario_academico.integer' => 'La cantidad de semanas para el lapso 1 debe ser un número.',
            'semana_lapso_uno_calendario_academico.min' => 'El mínimo de semanas para el lapso 1 es 1.',
            'semana_lapso_uno_calendario_academico.max' => 'El máximo de semanas para el lapso 1 es 99.',
            'semana_lapso_dos_calendario_academico.required' => 'La cantidad de semanas para el lapso 2 es obligatoria.',
            'semana_lapso_dos_calendario_academico.integer' => 'La cantidad de semanas para el lapso 2 debe ser un número.',
            'semana_lapso_dos_calendario_academico.min' => 'El mínimo de semanas para el lapso 2 es 1.',
            'semana_lapso_dos_calendario_academico.max' => 'El máximo de semanas para el lapso 2 es 99.',
            'dia_inicio_calendario_academico.required' => 'La fecha de inicio es obligatoria.',
            'dia_inicio_calendario_academico.date' => 'La fecha de inicio debe ser válida.',
            'dia_fin_calendario_academico.required' => 'La fecha de fin es obligatoria.',
            'dia_fin_calendario_academico.date' => 'La fecha de fin debe ser válida.',
            'dia_fin_calendario_academico.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'semana_lapso_uno_introductorio_calendario_academico.required' => 'La cantidad de semanas para el lapso introductorio 1 es obligatoria.',
            'semana_lapso_uno_introductorio_calendario_academico.integer' => 'La cantidad de semanas para el lapso introductorio 1 debe ser un número.',
            'semana_lapso_uno_introductorio_calendario_academico.min' => 'El mínimo de semanas para el lapso introductorio 1 es 0.',
            'semana_lapso_uno_introductorio_calendario_academico.max' => 'El máximo de semanas para el lapso introductorio 1 es 99.',
            'semana_lapso_dos_introductorio_calendario_academico.required' => 'La cantidad de semanas para el lapso introductorio 2 es obligatoria.',
            'semana_lapso_dos_introductorio_calendario_academico.integer' => 'La cantidad de semanas para el lapso introductorio 2 debe ser un número.',
            'semana_lapso_dos_introductorio_calendario_academico.min' => 'El mínimo de semanas para el lapso introductorio 2 es 0.',
            'semana_lapso_dos_introductorio_calendario_academico.max' => 'El máximo de semanas para el lapso introductorio 2 es 99.',
            'semana_intensibo_introductorio_calendario_academico.required' => 'La cantidad de semanas para el curso intensivo es obligatoria.',
            'semana_intensibo_introductorio_calendario_academico.integer' => 'La cantidad de semanas para el curso intensivo debe ser un número.',
            'semana_intensibo_introductorio_calendario_academico.min' => 'El mínimo de semanas para el curso intensivo es 0.',
            'semana_intensibo_introductorio_calendario_academico.max' => 'El máximo de semanas para el curso intensivo es 99.',
            // Mensajes para registro rápido
            'nombreEventoTemporal.required' => 'La descripción es obligatoria.',
            'nombreEventoTemporal.max' => 'La descripción no debe exceder 100 caracteres.',
                        'nombreEventoTemporal.regex' => 'Formato inválido en la descripción.',
            'nombreEventoTemporal.string' => 'La descripción debe ser texto.',
            'nuevoTipo.required' => 'El tipo de evento es obligatorio.',
            'nuevoTipo.in' => 'El tipo de evento no es válido.',
            'nuevoColorHex.required' => 'El color es obligatorio.',
            'nuevoColorHex.string' => 'El color debe ser texto.',
            'nuevoColorHex.regex' => 'El código de color debe tener formato hexadecimal (ej: #FF0000).',
            'nuevoLaborable.required' => 'El campo laborable es obligatorio.',
            'nuevoLaborable.boolean' => 'El campo laborable debe ser un valor booleano.',
            'nuevoRepetible.required' => 'El campo repetible es obligatorio.',
            'nuevoRepetible.boolean' => 'El campo repetible debe ser un valor booleano.',
            'nuevoIsRangoDias.required' => 'El campo rango de días es obligatorio.',
            'nuevoIsRangoDias.boolean' => 'El campo rango de días debe ser un valor booleano.',
            'nuevoIsIndependiente.required' => 'El campo independiente es obligatorio.',
            'nuevoIsIndependiente.boolean' => 'El campo independiente debe ser un valor booleano.',
            'nuevoIsSuperponible.required' => 'El campo superponible es obligatorio.',
            'nuevoIsSuperponible.boolean' => 'El campo superponible debe ser un valor booleano.',
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
                'in:1,2,3,4,5,6'
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
        $allRules = $this->rules();
        $this->validate([
            'dia_inicio_calendario_academico' => $allRules['dia_inicio_calendario_academico'],
            'dia_fin_calendario_academico' => $allRules['dia_fin_calendario_academico'],
            'semana_lapso_uno_calendario_academico' => $allRules['semana_lapso_uno_calendario_academico'],
            'semana_lapso_dos_calendario_academico' => $allRules['semana_lapso_dos_calendario_academico'],
            'semana_lapso_uno_introductorio_calendario_academico' => $allRules['semana_lapso_uno_introductorio_calendario_academico'],
            'semana_lapso_dos_introductorio_calendario_academico' => $allRules['semana_lapso_dos_introductorio_calendario_academico'],
            'semana_intensibo_introductorio_calendario_academico' => $allRules['semana_intensibo_introductorio_calendario_academico'],
        ]);

        // Cálculo de Semanas Exactas (Sin forzar Lunes-Domingo)
        $inicioReal = \Carbon\Carbon::parse($this->dia_inicio_calendario_academico);
        $finReal = \Carbon\Carbon::parse($this->dia_fin_calendario_academico);

        // Validar que la duración no supere los 18 meses máximo
        $limite18Meses = $inicioReal->copy()->addMonthsNoOverflow(18);

        if ($finReal->gt($limite18Meses)) {
            $msg = "El período académico no puede durar más de 18 meses.";
            $this->addError('dia_fin_calendario_academico', $msg);
            throw \Illuminate\Validation\ValidationException::withMessages(['form.dia_fin_calendario_academico' => [$msg]]);
        }
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

        // 2. Validar que todos los eventos activos estén asignados (ahora todos son obligatorios)
        $eventosObligatorios = \App\Models\Evento::where('estatus', '1')
            ->get();

        $idsRegistrados = collect($eventosRegistrados)->pluck('id')->all();

        foreach ($eventosObligatorios as $obligatorio) {
            // 2.1 Validar que el evento esté registrado
            if (!in_array($obligatorio->id_evento, $idsRegistrados)) {
                $msg = "El evento \"{$obligatorio->nombre_evento}\" debe ser asignado al calendario antes de guardar.";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
                continue;
            }
        }

        // 3. Validar eventos especiales y eventos no repetibles
        $eventosDb = \App\Models\Evento::whereIn('id_evento', $idsRegistrados)->get()->keyBy('id_evento');
        $inicios = [];
        $fines = [];
        $inicios_intro = [];
        $fines_intro = [];
        $inicios_intensi = [];
        $fines_intensi = [];

        foreach ($eventosRegistrados as $reg) {
            $id = $reg['id'] ?? null;
            if ($id && isset($eventosDb[$id])) {
                $evento = $eventosDb[$id];
                if ($evento->especial_evento == '2') {
                    $inicios[] = $reg['inicio'] ?? null;
                } elseif ($evento->especial_evento == '3') {
                    $fines[] = $reg['fin'] ?? null;
                } elseif ($evento->especial_evento == '7') {
                    $inicios_intro[] = $reg['inicio'] ?? null;
                } elseif ($evento->especial_evento == '8') {
                    $fines_intro[] = $reg['fin'] ?? null;
                } elseif ($evento->especial_evento == '9') {
                    $inicios_intensi[] = $reg['inicio'] ?? null;
                } elseif ($evento->especial_evento == '10') {
                    $fines_intensi[] = $reg['fin'] ?? null;
                }
            }
        }

        $inicios = array_filter(array_unique($inicios));
        $fines = array_filter(array_unique($fines));
        $inicios_intro = array_filter(array_unique($inicios_intro));
        $fines_intro = array_filter(array_unique($fines_intro));
        $inicios_intensi = array_filter(array_unique($inicios_intensi));
        $fines_intensi = array_filter(array_unique($fines_intensi));

        sort($inicios);
        sort($fines);
        sort($inicios_intro);
        sort($fines_intro);
        sort($inicios_intensi);
        sort($fines_intensi);

        foreach ($eventosRegistrados as $reg) {
            $id = $reg['id'] ?? null;
            if ($id && isset($eventosDb[$id])) {
                $evento = $eventosDb[$id];

                // Validar duración exacta de cada instancia si el evento tiene rango de días específico (excepto Vacaciones)
                if ($evento->is_cantidad_dias_evento && $evento->especial_evento != '1') {
                    $inicio = new \DateTime($reg['inicio']);
                    $fin = new \DateTime($reg['fin']);
                    $diferencia = $inicio->diff($fin)->days + 1;
                    if ($diferencia != $evento->cantidad_dias_evento) {
                        $msg = "Cada instancia del evento \"{$evento->nombre_evento}\" debe durar exactamente {$evento->cantidad_dias_evento} días (actualmente dura {$diferencia} días).";
                        $this->addError('eventosRegistrados', $msg);
                        $errores[] = [$msg];
                    }
                }

                // Validar que los únicos eventos para sábados y domingos sean Feriados (tipo 1 y 2)
                $tipo = (string) $evento->tipo_evento;
                $start = new \DateTime($reg['inicio']);
                $end = new \DateTime($reg['fin']);
                $todoEsWeekend = true;
                $interval = new \DateInterval('P1D');
                $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
                foreach ($period as $date) {
                    if ((int) $date->format('N') < 6) {
                        $todoEsWeekend = false;
                        break;
                    }
                }

                if ($todoEsWeekend && !in_array($tipo, ['1', '2', '6'])) {
                    $msg = "El evento \"{$evento->nombre_evento}\" está asignado en fin de semana, lo cual solo se permite para Feriados Nacionales o Locales.";
                    $this->addError('eventosRegistrados', $msg);
                    $errores[] = [$msg];
                }

                // Los contadores se validan al final usando los arrays ordenados

                // Validar que las fechas del evento estén comprendidas dentro del rango del calendario académico
                $calInicio = $this->dia_inicio_calendario_academico;
                $calFin = $this->dia_fin_calendario_academico;
                $regInicio = $reg['inicio'] ?? null;
                $regFin = $reg['fin'] ?? null;

                $calFuera = ($regInicio && ($regInicio < $calInicio || $regInicio > $calFin)) ||
                    ($regFin && ($regFin < $calInicio || $regFin > $calFin));

                if ($calFuera) {
                    $msg = "El evento \"{$evento->nombre_evento}\" debe estar comprendido dentro del período académico ({$calInicio} al {$calFin}).";
                    $this->addError('eventosRegistrados', $msg);
                    $errores[] = [$msg];
                }

                // Validar que eventos con is_independiente false estén dentro del rango de alguno de los periodos académicos
                $isIndependiente = $evento->is_independiente ?? $evento->is_independiente_evento ?? false;
                if (!$isIndependiente) {
                    $dentroDeAlgunPeriodo = false;

                    // Comprobar Lapsos 1 y 2
                    if (count($inicios) === 2 && count($fines) === 2) {
                        $dentroDeAlgunPeriodo = $dentroDeAlgunPeriodo || ($regInicio && $regInicio >= $inicios[0] && $regFin && $regFin <= $fines[0]);
                        $dentroDeAlgunPeriodo = $dentroDeAlgunPeriodo || ($regInicio && $regInicio >= $inicios[1] && $regFin && $regFin <= $fines[1]);
                    }
                    // Comprobar Introductorio
                    if (count($inicios_intro) > 0 && count($fines_intro) > 0 && count($inicios_intro) === count($fines_intro)) {
                        if (isset($inicios_intro[0]) && isset($fines_intro[0])) {
                            $dentroDeAlgunPeriodo = $dentroDeAlgunPeriodo || ($regInicio && $regInicio >= $inicios_intro[0] && $regFin && $regFin <= $fines_intro[0]);
                        }
                        if (isset($inicios_intro[1]) && isset($fines_intro[1])) {
                            $dentroDeAlgunPeriodo = $dentroDeAlgunPeriodo || ($regInicio && $regInicio >= $inicios_intro[1] && $regFin && $regFin <= $fines_intro[1]);
                        }
                    }
                    // Comprobar Intensivo
                    if (count($inicios_intensi) === 1 && count($fines_intensi) === 1) {
                        $dentroDeAlgunPeriodo = $dentroDeAlgunPeriodo || ($regInicio && $regInicio >= $inicios_intensi[0] && $regFin && $regFin <= $fines_intensi[0]);
                    }

                    if (!$dentroDeAlgunPeriodo) {
                        $msg = "El evento \"{$evento->nombre_evento}\" debe estar comprendido dentro de alguno de los periodos académicos (Lapsos, Introductorio o Intensivo).";
                        $this->addError('eventosRegistrados', $msg);
                        $errores[] = [$msg];
                    }
                }

                // Validar que los eventos no repetibles (como feriados de tipo 1 y 2) solo ocurran 1 vez por año en toda la base de datos
                if (!$evento->is_repetible_evento && in_array($evento->tipo_evento, ['1', '2'])) {
                    $year = date('Y', strtotime($reg['inicio']));

                    // Verificar si ya se asignó más de una vez en este mismo request/calendario
                    $key = $id . '_' . $year;
                    if (in_array($key, $visitados)) {
                        $msg = "El evento no repetible \"{$evento->nombre_evento}\" no se puede asignar más de una vez en el mismo año.";
                        $this->addError('eventosRegistrados', $msg);
                        $errores[] = [$msg];
                        continue;
                    }
                    $visitados[] = $key;

                    // Verificar en otros calendarios del mismo año
                    $query = DB::table('detalle_evento as de')
                        ->join('calendario_academico as ca', 'de.id_calendario_academico', '=', 'ca.id_calendario_academico')
                        ->where('de.id_evento', $id)
                        ->where('de.estatus', 1)
                        ->where('ca.estatus', 1)
                        ->whereYear('ca.dia_inicio_calendario_academico', $year);

                    // Si estamos editando, omitimos el calendario actual
                    if (isset($this->id_calendario_academico) && !empty($this->id_calendario_academico)) {
                        $query->where('ca.id_calendario_academico', '!=', $this->id_calendario_academico);
                    }

                    $countInDb = $query->count();
                    if ($countInDb > 0) {
                        $msg = "El evento no repetible \"{$evento->nombre_evento}\" ya está registrado en otro calendario académico para el año {$year}.";
                        $this->addError('eventosRegistrados', $msg);
                        $errores[] = [$msg];
                    }
                }
            }
        }

        if (count($inicios) !== 2) {
            $msg = "El calendario debe contener exactamente dos eventos especiales de tipo \"Inicio del Lapso Académico\".";
            $this->addError('eventosRegistrados', $msg);
            $errores[] = [$msg];
        }

        if (count($fines) !== 2) {
            $msg = "El calendario debe contener exactamente dos eventos especiales de tipo \"Fin del Lapso Académico\".";
            $this->addError('eventosRegistrados', $msg);
            $errores[] = [$msg];
        }
        $expectedIntros = 0;
        if ($this->semana_lapso_uno_introductorio_calendario_academico > 0)
            $expectedIntros++;
        if ($this->semana_lapso_dos_introductorio_calendario_academico > 0)
            $expectedIntros++;

        if ($expectedIntros > 0) {
            if (count($inicios_intro) !== $expectedIntros || count($fines_intro) !== $expectedIntros) {
                $msg = "Debe haber exactamente {$expectedIntros} Inicio(s) y {$expectedIntros} Fin(es) de Lapso Introductorio basado en la configuración de semanas.";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }
        } elseif (count($inicios_intro) > 0 || count($fines_intro) > 0) {
            $msg = "No puede agregar eventos de Lapso Introductorio si configuró sus semanas en 0.";
            $this->addError('eventosRegistrados', $msg);
            $errores[] = [$msg];
        }

        if ($this->semana_intensibo_introductorio_calendario_academico > 0) {
            if (count($inicios_intensi) !== 1 || count($fines_intensi) !== 1) {
                $msg = "Debe haber exactamente un Inicio y un Fin de Curso Intensivo si configuró semanas mayores a 0.";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }
        } elseif (count($inicios_intensi) > 0 || count($fines_intensi) > 0) {
            $msg = "No puede agregar eventos de Curso Intensivo si configuró sus semanas en 0.";
            $this->addError('eventosRegistrados', $msg);
            $errores[] = [$msg];
        }

        $periodosRegistrados = [];

        if (count($inicios) === 2 && count($fines) === 2) {
            $periodosRegistrados['Lapso 1'] = ['nombre' => 'Lapso 1', 'inicio' => $inicios[0], 'fin' => $fines[0], 'semanas_configuradas' => $this->semana_lapso_uno_calendario_academico];
            $periodosRegistrados['Lapso 2'] = ['nombre' => 'Lapso 2', 'inicio' => $inicios[1], 'fin' => $fines[1], 'semanas_configuradas' => $this->semana_lapso_dos_calendario_academico];
        }
        if (count($inicios_intro) > 0 && count($fines_intro) > 0 && count($inicios_intro) === count($fines_intro)) {
            if (isset($inicios_intro[0]) && isset($fines_intro[0]) && $this->semana_lapso_uno_introductorio_calendario_academico > 0) {
                $periodosRegistrados['Lapso 1 Introductorio'] = ['nombre' => 'Lapso 1 Introductorio', 'inicio' => $inicios_intro[0], 'fin' => $fines_intro[0], 'semanas_configuradas' => $this->semana_lapso_uno_introductorio_calendario_academico];
            }
            if (isset($inicios_intro[1]) && isset($fines_intro[1]) && $this->semana_lapso_dos_introductorio_calendario_academico > 0) {
                $periodosRegistrados['Lapso 2 Introductorio'] = ['nombre' => 'Lapso 2 Introductorio', 'inicio' => $inicios_intro[1], 'fin' => $fines_intro[1], 'semanas_configuradas' => $this->semana_lapso_dos_introductorio_calendario_academico];
            }
        }
        if (count($inicios_intensi) === 1 && count($fines_intensi) === 1) {
            $periodosRegistrados['Curso Intensivo'] = ['nombre' => 'Curso Intensivo', 'inicio' => $inicios_intensi[0], 'fin' => $fines_intensi[0], 'semanas_configuradas' => $this->semana_intensibo_introductorio_calendario_academico];
        }

        foreach ($periodosRegistrados as $key => $periodo) {
            if ($periodo['inicio'] > $periodo['fin']) {
                $msg = "El periodo '{$periodo['nombre']}' tiene una fecha de inicio posterior a su fecha de fin.";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }

            // Validar cantidad de semanas asignadas
            $incluirVacaciones = $periodo['nombre'] !== 'Curso Intensivo';
            $semanasReales = \App\Support\CalendarioLapsoSemanas::contarSemanas($periodo['inicio'], $periodo['fin'], $eventosRegistrados, $incluirVacaciones);
            if ($semanasReales != $periodo['semanas_configuradas']) {
                $msg = "Las fechas asignadas para '{$periodo['nombre']}' abarcan {$semanasReales} semanas, pero se estipularon {$periodo['semanas_configuradas']}.";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }
        }

        $paresNoSolapables = [
            ['Lapso 1', 'Lapso 2'],
            ['Lapso 1', 'Curso Intensivo'],
            ['Lapso 2', 'Curso Intensivo'],
            ['Lapso 1 Introductorio', 'Lapso 2 Introductorio'],
            ['Lapso 1 Introductorio', 'Curso Intensivo'],
            ['Lapso 2 Introductorio', 'Curso Intensivo']
        ];

        foreach ($paresNoSolapables as $par) {
            $p1 = $par[0];
            $p2 = $par[1];
            if (isset($periodosRegistrados[$p1]) && isset($periodosRegistrados[$p2])) {
                $per1 = $periodosRegistrados[$p1];
                $per2 = $periodosRegistrados[$p2];

                // Check overlap: max(start1, start2) <= min(end1, end2)
                if (max($per1['inicio'], $per2['inicio']) <= min($per1['fin'], $per2['fin'])) {
                    $msg = "Los periodos no pueden solaparse. '{$per1['nombre']}' ({$per1['inicio']} al {$per1['fin']}) choca con '{$per2['nombre']}' ({$per2['inicio']} al {$per2['fin']}).";
                    $this->addError('eventosRegistrados', $msg);
                    $errores[] = [$msg];
                }
            }
        }

        // 4. Validar suma de días de vacaciones colectivas (evento especial 1) por año
        $repo = new \App\Repositories\Calendario\CalendarioCreateRepo();
        $eventoVacaciones = $repo->obtenerEventoVacacionesActivo();
        if ($eventoVacaciones) {
            $diasPorAnio = [];
            foreach ($eventosRegistrados as $reg) {
                if (($reg['id'] ?? null) == $eventoVacaciones->id_evento) {
                    $start = new \DateTime($reg['inicio']);
                    $end = new \DateTime($reg['fin']);

                    $isTodoWeekend = true;
                    $tempInterval = new \DateInterval('P1D');
                    $tempPeriod = new \DatePeriod($start, $tempInterval, (clone $end)->modify('+1 day'));
                    foreach ($tempPeriod as $date) {
                        if ((int) $date->format('N') < 6) {
                            $isTodoWeekend = false;
                            break;
                        }
                    }
                    $ignorarFinesDeSemana = !in_array($reg['tipo'] ?? '1', ['1', '2', '6']) && !$isTodoWeekend;

                    $interval = new \DateInterval('P1D');
                    $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
                    foreach ($period as $date) {
                        if ($ignorarFinesDeSemana && (int) $date->format('N') >= 6) {
                            continue;
                        }
                        $year = $date->format('Y');
                        if (!isset($diasPorAnio[$year])) {
                            $diasPorAnio[$year] = 0;
                        }
                        $diasPorAnio[$year]++;
                    }
                }
            }

            foreach ($diasPorAnio as $year => $diasActuales) {
                $diasEnOtrosCalendarios = 0; // REQUERIMIENTO: IGNORAR OTROS CALENDARIOS

                $totalDiasVacaciones = $diasActuales + $diasEnOtrosCalendarios;
                $cantidadRequerida = $eventoVacaciones->cantidad_dias_evento ?? 60;

                if ($totalDiasVacaciones != $cantidadRequerida) {
                    $msg = "La suma total de días de vacaciones colectivas asignados para el año {$year} ({$totalDiasVacaciones} días) debe ser exactamente igual a los {$cantidadRequerida} días configurados en el evento.";
                    $this->addError('eventosRegistrados', $msg);
                    $errores[] = [$msg];
                }
            }
        }

        if (count($errores) > 0) {
            // Aplanar array de errores si es necesario
            $todosLosErrores = [];
            foreach ($errores as $err) {
                if (is_array($err)) {
                    foreach ($err as $e)
                        $todosLosErrores[] = $e;
                } else {
                    $todosLosErrores[] = $err;
                }
            }
            return ['valido' => false, 'errores' => $todosLosErrores];
        }

        return ['valido' => true, 'errores' => []];
    }

    /**
     * Valida que si el rango de asignación del evento es fin de semana, el evento sea Feriado (tipo 1 o 2).
     */
    public function validarRangoEvento($inicio, $fin, $tipo)
    {
        $start = new \DateTime($inicio);
        $end = new \DateTime($fin);
        $todoEsWeekend = true;
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
        foreach ($period as $date) {
            if ((int) $date->format('N') < 6) {
                $todoEsWeekend = false;
                break;
            }
        }

        if ($todoEsWeekend && !in_array($tipo, ['1', '2', '6'])) {
            $msg = "Los fines de semana (sábados y domingos) solo admiten eventos de tipo Feriado Nacional o Feriado Local.";
            $this->addError('eventosRegistrados', $msg);
            throw \Illuminate\Validation\ValidationException::withMessages(['eventosRegistrados' => [$msg]]);
        }
    }
}

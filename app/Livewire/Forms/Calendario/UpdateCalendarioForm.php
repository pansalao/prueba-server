<?php

namespace App\Livewire\Forms\Calendario;

use Livewire\Form;
use Illuminate\Support\Facades\DB;

class UpdateCalendarioForm extends Form
{
    public $id_calendario_academico = '';
    public $semana_calendario_academico = '';
    public $dia_inicio_calendario_academico = '';
    public $dia_fin_calendario_academico = '';
    public $tipo_calendario = '1';

    // Propiedades para registro rápido de eventos (Paridad con CreateCalendarioForm)
    public $nombreEventoTemporal = '';
    public $nuevoColorId = '';
    public $nuevoTipo = '1';
    public $nuevoLaborable = false;
    public $nuevoRepetible = false;
    public $nuevoIsRangoDias = false;
    public $nuevoRangoDias = '';
    public $nuevoIsIndependiente = true;
    public $idEventoTemporal = null;
    public $isCreatingEvento = false;

    public function rules()
    {
        $rules = [
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
                        $repo = new \App\Repositories\Calendario\CalendarioUpdateRepo();
                        if ($repo->existeEventoConNombre($value, $this->idEventoTemporal)) {
                            $fail($this->idEventoTemporal ? 'Ya existe otro evento con esta descripción.' : 'Ya existe un evento con esta descripción.');
                        }
                    },
                    'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑüÜ\d\s\.,\-\(\)\"\':\/]+$/u'
                ],
                'nuevoTipo' => ['required', 'in:1,2,3,4,5'],
                'nuevoLaborable' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (($this->nuevoTipo == '1' || $this->nuevoTipo == '2') && $value) {
                            $fail('Un feriado no puede ser marcado como laborable.');
                        }
                    }
                ],
                'nuevoRepetible' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (($this->nuevoTipo == '1' || $this->nuevoTipo == '2') && $value) {
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
                    'required_if:nuevoIsRangoDias,true',
                    'numeric',
                    'min:1',
                    'max:90'
                ],
                'nuevoIsIndependiente' => [
                    'required',
                    'boolean',
                    function ($attribute, $value, $fail) {
                        if (in_array($this->nuevoTipo, ['1', '2']) && !$value) {
                            $fail('Para los feriados nacionales y locales, el evento debe ser obligatoriamente Independiente.');
                        }
                    }
                ],
                'nuevoColorId' => [
                    'required',
                    'exists:color,id_color',
                    function ($attribute, $value, $fail) {
                        $repo = new \App\Repositories\Calendario\CalendarioUpdateRepo();
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
            'nombreEventoTemporal.required' => 'La descripción es obligatoria.',
            'nombreEventoTemporal.max' => 'La descripción no debe exceder 100 caracteres.',
            'nombreEventoTemporal.regex' => 'Formato inválido en la descripción.',
            'nuevoTipo.required' => 'El tipo de evento es obligatorio.',
            'nuevoColorId.required' => 'El color es obligatorio.',
            'nuevoIsIndependiente.required' => 'El campo independiente es obligatorio.',
            'nuevoIsIndependiente.boolean' => 'El campo independiente debe ser un valor booleano.',
        ];
    }

    public function setCalendario($calendario)
    {
        $this->id_calendario_academico = $calendario->id_calendario_academico;
        $this->semana_calendario_academico = $calendario->semana_calendario_academico;
        $this->dia_inicio_calendario_academico = $calendario->dia_inicio_calendario_academico;
        $this->dia_fin_calendario_academico = $calendario->dia_fin_calendario_academico;
        $this->tipo_calendario = $calendario->tipo_calendario ?? '1';
    }

    public function validarEvento($nombre, $tipo)
    {
        $reglas = [
            'nombre' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑüÜ\d\s\.\,\-\(\)\"\':\/]+$/u'],
            'tipo' => ['required', 'in:1,2,3,4,5'],
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

    public function validarSeccionFechas()
    {
        $allRules = $this->rules();
        $this->validate([
            'dia_inicio_calendario_academico' => $allRules['dia_inicio_calendario_academico'],
            'dia_fin_calendario_academico' => $allRules['dia_fin_calendario_academico'],
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

    public function validarFormularioCompleto($eventosRegistrados)
    {
        $errores = [];

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
        $visitados = [];

        foreach ($eventosRegistrados as $reg) {
            $id = $reg['id'] ?? null;
            if ($id && isset($eventosDb[$id])) {
                $evento = $eventosDb[$id];
                if ($evento->especial_evento == '2') {
                    $inicios[] = $reg['inicio'] ?? null;
                } elseif ($evento->especial_evento == '3') {
                    $fines[] = $reg['fin'] ?? null;
                }
            }
        }

        $inicios = array_filter(array_unique($inicios));
        $fines = array_filter(array_unique($fines));
        sort($inicios);
        sort($fines);

        foreach ($eventosRegistrados as $reg) {
            $id = $reg['id'] ?? null;
            if ($id && isset($eventosDb[$id])) {
                $evento = $eventosDb[$id];

                // Validar duración exacta de cada instancia si el evento tiene rango de días específico
                if ($evento->is_rango_dias_evento) {
                    $inicio = new \DateTime($reg['inicio']);
                    $fin = new \DateTime($reg['fin']);
                    $diferencia = $inicio->diff($fin)->days + 1;
                    if ($diferencia != $evento->rango_dias_evento) {
                        $msg = "Cada instancia del evento \"{$evento->nombre_evento}\" debe durar exactamente {$evento->rango_dias_evento} días (actualmente dura {$diferencia} días).";
                        $this->addError('eventosRegistrados', $msg);
                        $errores[] = [$msg];
                    }
                }

                // Validar que los únicos eventos para sábados y domingos sean Feriados (tipo 1 y 2)
                $tipo = (string)$evento->tipo_evento;
                $start = new \DateTime($reg['inicio']);
                $end = new \DateTime($reg['fin']);
                $todoEsWeekend = true;
                $interval = new \DateInterval('P1D');
                $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
                foreach ($period as $date) {
                    if ((int)$date->format('N') < 6) {
                        $todoEsWeekend = false;
                        break;
                    }
                }

                if ($todoEsWeekend && !in_array($tipo, ['1', '2'])) {
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

                // Validar que eventos con is_independiente false estén dentro del rango de alguno de los dos lapsos académicos
                $isIndependiente = $evento->is_independiente ?? $evento->is_independiente_evento ?? false;
                if (!$isIndependiente) {
                    if (count($inicios) === 2 && count($fines) === 2) {
                        $S1 = $inicios[0];
                        $E1 = $fines[0];
                        $S2 = $inicios[1];
                        $E2 = $fines[1];

                        $dentroLapso1 = ($regInicio && $regInicio >= $S1 && $regFin && $regFin <= $E1);
                        $dentroLapso2 = ($regInicio && $regInicio >= $S2 && $regFin && $regFin <= $E2);

                        if (!$dentroLapso1 && !$dentroLapso2) {
                            $msg = "El evento \"{$evento->nombre_evento}\" debe estar comprendido dentro de alguno de los dos lapsos académicos (Primer Lapso: {$S1} al {$E1}, Segundo Lapso: {$S2} al {$E2}).";
                            $this->addError('eventosRegistrados', $msg);
                            $errores[] = [$msg];
                        }
                    } else {
                        $msg = "El evento \"{$evento->nombre_evento}\" requiere que estén registrados exactamente dos lapsos académicos para validar su ubicación.";
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

        if (count($inicios) === 2 && count($fines) === 2) {
            $S1 = $inicios[0];
            $E1 = $fines[0];
            $S2 = $inicios[1];
            $E2 = $fines[1];

            if ($S1 > $E1) {
                $msg = "El primer lapso académico tiene una fecha de inicio ({$S1}) posterior a su fecha de fin ({$E1}).";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }

            if ($S2 > $E2) {
                $msg = "El segundo lapso académico tiene una fecha de inicio ({$S2}) posterior a su fecha de fin ({$E2}).";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }

            if ($S2 < $E1) {
                $msg = "Los lapsos académicos no pueden solaparse ni estar contenidos uno dentro del otro. El segundo lapso debe iniciar el mismo día o después de que termine el primer lapso (Fin del primer lapso: {$E1}, Inicio del segundo lapso: {$S2}).";
                $this->addError('eventosRegistrados', $msg);
                $errores[] = [$msg];
            }
        }

        // 4. Validar suma de días de vacaciones colectivas (evento especial 1) por año
        $repo = new \App\Repositories\Calendario\CalendarioUpdateRepo();
        $eventoVacaciones = $repo->obtenerEventoVacacionesActivo();
        if ($eventoVacaciones) {
            $diasPorAnio = [];
            foreach ($eventosRegistrados as $reg) {
                if (($reg['id'] ?? null) == $eventoVacaciones->id_evento) {
                    $start = new \DateTime($reg['inicio']);
                    $end = new \DateTime($reg['fin']);
                    
                    $interval = new \DateInterval('P1D');
                    $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
                    foreach ($period as $date) {
                        $year = $date->format('Y');
                        if (!isset($diasPorAnio[$year])) {
                            $diasPorAnio[$year] = 0;
                        }
                        $diasPorAnio[$year]++;
                    }
                }
            }

            foreach ($diasPorAnio as $year => $diasActuales) {
                $excluirId = (isset($this->id_calendario_academico) && !empty($this->id_calendario_academico)) ? $this->id_calendario_academico : null;
                $diasEnOtrosCalendarios = $repo->obtenerDiasVacacionesEnOtrosCalendarios($eventoVacaciones->id_evento, $year, $excluirId);

                $totalDiasVacaciones = $diasActuales + $diasEnOtrosCalendarios;
                $cantidadRequerida = $eventoVacaciones->cantidad_dias_evento ?? 60;

                if ($totalDiasVacaciones != $cantidadRequerida) {
                    $msg = "La suma total de días de vacaciones colectivas asignados para el año {$year} ({$totalDiasVacaciones} días) debe ser exactamente igual a los {$cantidadRequerida} días configurados en el evento. ";
                    if ($diasEnOtrosCalendarios > 0) {
                        $diasRestantes = $cantidadRequerida - $diasEnOtrosCalendarios;
                        if ($diasRestantes > 0) {
                            $msg .= "(Ya se encuentran asignados {$diasEnOtrosCalendarios} días en otros períodos de este año, por lo que debe asignar exactamente {$diasRestantes} días en este período).";
                        } else {
                            $msg .= "(Ya se encuentran asignados {$diasEnOtrosCalendarios} días en otros períodos de este año, superando o completando la cantidad permitida).";
                        }
                    }
                    $this->addError('eventosRegistrados', $msg);
                    $errores[] = [$msg];
                }
            }
        }

        if (count($errores) > 0) {
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
            if ((int)$date->format('N') < 6) {
                $todoEsWeekend = false;
                break;
            }
        }

        if ($todoEsWeekend && !in_array($tipo, ['1', '2'])) {
            $msg = "Los fines de semana (sábados y domingos) solo admiten eventos de tipo Feriado Nacional o Feriado Local.";
            $this->addError('eventosRegistrados', $msg);
            throw \Illuminate\Validation\ValidationException::withMessages(['eventosRegistrados' => [$msg]]);
        }
    }
}

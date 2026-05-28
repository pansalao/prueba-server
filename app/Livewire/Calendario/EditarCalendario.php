<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Livewire\Forms\Calendario\UpdateCalendarioForm;
use App\Repositories\Calendario\CalendarioUpdateRepo;
use App\Repositories\Calendario\CalendarioViewRepo;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class EditarCalendario extends Component
{
    public UpdateCalendarioForm $form;
    protected $calendarioRepository;
    protected $viewRepository;

    public $eventosRegistrados = [];
    public $eventosPorFecha = []; // Mapa de eventos agrupados por fecha
    public $bibliotecaEventos = [];
    public $currentYear;
    public $id_calendario;
    public $selectedYearTemporal = null;

    public function boot()
    {
        $this->calendarioRepository = new CalendarioUpdateRepo();
        $this->viewRepository = new CalendarioViewRepo();
    }

    public function mount($id)
    {
        if (!Gate::allows('ver-calendario')) {
            abort(403);
        }

        $this->id_calendario = $id;
        $calendario = $this->viewRepository->mostrar($id);

        if (!$calendario || $calendario->estatus != 2) {
            return redirect()->route('calendario.list')->with('error', 'El calendario no está en revisión o no existe.');
        }

        // Cargar datos en el formulario
        $this->form->setCalendario($calendario);

        $this->currentYear = date('Y', strtotime($calendario->dia_inicio_calendario_academico));

        // Cargar eventos registrados
        $eventos = $this->calendarioRepository->obtenerEventosDetalle($id);

        foreach ($eventos as $ev) {
            $this->eventosRegistrados[] = [
                'id' => (int) $ev->id,
                'inicio' => (string) $ev->inicio,
                'fin' => (string) $ev->fin,
                'nombre' => (string) $ev->nombre,
                'nombre_evento' => (string) $ev->nombre,
                'tipo' => (string) $ev->tipo,
                'color' => (string) $ev->color,
                'is_cantidad_dias_evento' => (bool) ($ev->is_cantidad_dias_evento ?? false),
                'cantidad_dias_evento' => $ev->cantidad_dias_evento ?? null,
                'especial_evento' => isset($ev->especial_evento) ? (string) $ev->especial_evento : null,
                'is_superponible_evento' => (bool) ($ev->is_superponible_evento ?? false),
            ];
        }

        // Cargar la biblioteca de eventos (templates)
        $eventoRepo = new EventoIndexRepo();
        $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
        $this->actualizarMapaEventos();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName == 'form.dia_inicio_calendario_academico' || $propertyName == 'form.dia_fin_calendario_academico') {
            $this->guardarBorrador();
        }

        if ($propertyName === 'form.nuevoTipo') {
            if (in_array($this->form->nuevoTipo, ['1', '2', '6'])) {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = false;
                $this->form->nuevoIsIndependiente = true;
            } else {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = true;
                $this->form->nuevoIsIndependiente = false;
            }
        }
    }

    protected function filtrarEventosFueraDeRango()
    {
        $inicio = $this->form->dia_inicio_calendario_academico;
        $fin = $this->form->dia_fin_calendario_academico;

        if (!$inicio || !$fin)
            return;

        $this->eventosRegistrados = array_filter($this->eventosRegistrados, function ($evento) use ($inicio, $fin) {
            return ($evento['inicio'] >= $inicio && $evento['inicio'] <= $fin) &&
                ($evento['fin'] >= $inicio && $evento['fin'] <= $fin);
        });

        $this->eventosRegistrados = array_values($this->eventosRegistrados);
        $this->actualizarMapaEventos();
    }

    public function agregarEvento($inicio, $fin, $id_evento, $nombre = null, $tipo = null, $color = null)
    {
        $eventoInfo = \App\Models\Evento::find($id_evento);

        // Buscar info fresca de la base de datos
        if (!$eventoInfo) {
            $eventoRepo = new \App\Repositories\Evento\EventoIndexRepo();
            $biblioteca = $eventoRepo->obtenerBiblioteca();
            $info = collect($biblioteca)->firstWhere('id_evento', $id_evento);
            if ($info) {
                $nombre = (string) $info->nombre_evento;
                $color = (string) $info->codigo_color;
                $tipo = (string) $info->tipo_evento;
                $eventoInfo = clone $info;
            }
        } else {
            $nombre = (string) $eventoInfo->nombre_evento;
            // Obtener el color desde codigo_color_evento o fallback
            $color = (string) ($eventoInfo->codigo_color_evento ?: $color);
            $tipo = (string) ($eventoInfo->tipo_evento ?? $tipo ?? '');
        }

        // VALIDACIÓN DE SEMANAS ESPECÍFICAS
        $semanasPermitidas = [];
        if ($eventoInfo && $eventoInfo->is_semana_evento) {
            $semanasPermitidas = is_array($eventoInfo->semana_evento) ? $eventoInfo->semana_evento : (json_decode($eventoInfo->semana_evento, true) ?? []);
        }

        if (!empty($semanasPermitidas)) {
            // Buscar inicio de lapso académico (especial_evento = 2)
            $lapsosAcademicos = collect($this->eventosRegistrados)
                ->filter(fn($ev) => ($ev['especial_evento'] ?? '') === '2')
                ->sortByDesc('inicio');

            $lapsoActual = $lapsosAcademicos->firstWhere('inicio', '<=', $inicio);

            if (!$lapsoActual) {
                $this->showAlert('error', 'Este evento tiene semanas específicas asignadas y solo puede registrarse durante un Lapso Académico regular.');
                return;
            }

            // Verificar si hay un lapso de trayecto inicial o intensivo más reciente que el académico
            $otrosLapsos = collect($this->eventosRegistrados)
                ->filter(fn($ev) => in_array($ev['especial_evento'] ?? '', ['7', '9']))
                ->sortByDesc('inicio');
            $otroLapsoActual = $otrosLapsos->firstWhere('inicio', '<=', $inicio);

            if ($otroLapsoActual && $otroLapsoActual['inicio'] >= $lapsoActual['inicio']) {
                $this->showAlert('error', 'Este evento tiene semanas específicas y solo puede registrarse durante un Lapso Académico regular (no de Trayecto Inicial ni intensivo).');
                return;
            }

            $incluirVacaciones = true;

            $semanaInicio = \App\Support\CalendarioLapsoSemanas::contarSemanas($lapsoActual['inicio'], $inicio, $this->eventosRegistrados, $incluirVacaciones);
            $semanaFin = \App\Support\CalendarioLapsoSemanas::contarSemanas($lapsoActual['inicio'], $fin, $this->eventosRegistrados, $incluirVacaciones);

            if (!in_array($semanaInicio, $semanasPermitidas) || !in_array($semanaFin, $semanasPermitidas)) {
                $semanasStr = implode(', ', $semanasPermitidas);
                $this->showAlert('error', "El evento solo puede registrarse en la(s) semana(s): {$semanasStr} del Lapso Académico. (Semana seleccionada: {$semanaInicio}" . ($semanaInicio != $semanaFin ? " a {$semanaFin}" : "") . ")");
                return;
            }
        }

        // VALIDAR REGLA DE SUPERPOSICIÓN CON VACACIONES COLECTIVAS
        $is_superponible = $eventoInfo ? (bool) $eventoInfo->is_superponible_evento : false;
        $is_vacaciones = ($eventoInfo->especial_evento ?? '') === '1';

        if (!$is_superponible && !$is_vacaciones) {
            foreach ($this->eventosRegistrados as $evReg) {
                if (($evReg['especial_evento'] ?? '') === '1') {
                    if ($inicio <= $evReg['fin'] && $fin >= $evReg['inicio']) {
                        $this->showAlert('error', "El evento '{$nombre}' no es superponible y no puede registrarse en la misma fecha que las Vacaciones Colectivas.");
                        return;
                    }
                }
            }
        } else if ($is_vacaciones) {
            foreach ($this->eventosRegistrados as $evReg) {
                if (isset($evReg['is_superponible_evento']) && !$evReg['is_superponible_evento']) {
                    if ($inicio <= $evReg['fin'] && $fin >= $evReg['inicio']) {
                        $this->showAlert('error', "No se pueden registrar Vacaciones Colectivas en estas fechas porque choca con el evento '{$evReg['nombre_evento']}', el cual no es superponible.");
                        return;
                    }
                }
            }
        }

        // Analizar si el rango contiene fines de semana
        $start = new \DateTime($inicio);
        $end = new \DateTime($fin);
        $contieneWeekend = false;
        $todoEsWeekend = true;

        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));

        foreach ($period as $date) {
            $dayOfWeek = (int) $date->format('N');
            if ($dayOfWeek >= 6) {
                $contieneWeekend = true;
            } else {
                $todoEsWeekend = false;
            }
        }

        // Validar usando el objeto Form
        try {
            $this->form->validarRangoEvento($inicio, $fin, $tipo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showAlert('error', $e->validator->errors()->first());
            return;
        }

        $subrangos = [];

        // Feriados nacionales y locales (1 y 2), o si el rango completo está en fin de semana
        if (in_array($tipo, ['1', '2', '6']) || $todoEsWeekend) {
            $subrangos[] = ['inicio' => $inicio, 'fin' => $fin];
        } else {
            // Otros eventos: dividimos omitiendo los fines de semana
            $currentStart = null;
            $currentEnd = null;

            foreach ($period as $date) {
                $dayOfWeek = (int) $date->format('N');
                $isWeekend = ($dayOfWeek >= 6);

                if (!$isWeekend) {
                    if ($currentStart === null) {
                        $currentStart = $date->format('Y-m-d');
                    }
                    $currentEnd = $date->format('Y-m-d');
                } else {
                    if ($currentStart !== null) {
                        $subrangos[] = ['inicio' => $currentStart, 'fin' => $currentEnd];
                        $currentStart = null;
                    }
                }
            }

            if ($currentStart !== null) {
                $subrangos[] = ['inicio' => $currentStart, 'fin' => $currentEnd];
            }
        }

        // Calcular duración real que se va a insertar (sumando días de cada subrango)
        $duracionReal = 0;
        foreach ($subrangos as $sub) {
            $s = new \DateTime($sub['inicio']);
            $e = new \DateTime($sub['fin']);
            $duracionReal += $s->diff($e)->days + 1;
        }

        // VALIDACIÓN DE is_cantidad_dias_evento
        if ($eventoInfo && $eventoInfo->is_cantidad_dias_evento) {
            $cantidadRequerida = (int) $eventoInfo->cantidad_dias_evento;
            $is_vacaciones = ($eventoInfo->especial_evento ?? '') === '1';

            if ($is_vacaciones) {
                $diasRegistrados = 0;
                $targetYear = date('Y', strtotime($inicio));

                foreach ($this->eventosRegistrados as $reg) {
                    if (($reg['id'] ?? null) == $eventoInfo->id_evento) {
                        $sReg = new \DateTime($reg['inicio']);
                        $eReg = new \DateTime($reg['fin']);

                        $isTodoWeekendReg = true;
                        $tempInterval = new \DateInterval('P1D');
                        $tempPeriod = new \DatePeriod($sReg, $tempInterval, (clone $eReg)->modify('+1 day'));
                        foreach ($tempPeriod as $date) {
                            if ((int) $date->format('N') < 6) {
                                $isTodoWeekendReg = false;
                                break;
                            }
                        }
                        $ignorarFinesDeSemanaReg = !in_array($reg['tipo'] ?? '1', ['1', '2', '6']) && !$isTodoWeekendReg;

                        $periodReg = new \DatePeriod($sReg, $tempInterval, (clone $eReg)->modify('+1 day'));
                        foreach ($periodReg as $date) {
                            if ($ignorarFinesDeSemanaReg && (int) $date->format('N') >= 6) {
                                continue;
                            }
                            if ($date->format('Y') == $targetYear) {
                                $diasRegistrados++;
                            }
                        }
                    }
                }

                if (($diasRegistrados + $duracionReal) > $cantidadRequerida) {
                    $disponibles = max(0, $cantidadRequerida - $diasRegistrados);
                    $this->showAlert('error', "No puedes registrar {$duracionReal} día(s) de Vacaciones Colectivas porque solo quedan {$disponibles} día(s) disponibles de los {$cantidadRequerida} permitidos en el año {$targetYear}.");
                    return;
                }
            } else {
                if ($duracionReal != $cantidadRequerida) {
                    $this->showAlert('error', "El evento '{$nombre}' debe durar exactamente {$cantidadRequerida} día(s) por cada selección. Has seleccionado {$duracionReal} día(s).");
                    return;
                }
            }
        }

        foreach ($subrangos as $sub) {
            $nuevoEvento = [
                'id' => (int) $id_evento,
                'inicio' => (string) $sub['inicio'],
                'fin' => (string) $sub['fin'],
                'nombre_evento' => (string) $nombre,
                'tipo' => (string) $tipo,
                'color' => (string) $color,
                'is_cantidad_dias_evento' => $eventoInfo ? (bool) $eventoInfo->is_cantidad_dias_evento : false,
                'cantidad_dias_evento' => $eventoInfo ? $eventoInfo->cantidad_dias_evento : null,
                'especial_evento' => $eventoInfo ? (string) $eventoInfo->especial_evento : null,
                'is_superponible_evento' => $eventoInfo ? (bool) $eventoInfo->is_superponible_evento : false,
            ];

            $this->eventosRegistrados[] = $nuevoEvento;
        }

        $this->actualizarMapaEventos();

        if ($this->debeRecalcularFinesLapso($eventoInfo)) {
            $this->recalcularFinesLapso();
        }

        $this->guardarBorrador();
    }

    protected function debeRecalcularFinesLapso(?\App\Models\Evento $eventoInfo): bool
    {
        $hayInicios = collect($this->eventosRegistrados)->contains(
            fn($e) => in_array($e['especial_evento'] ?? '', ['2', '7', '9'])
        );

        if (!$hayInicios || !$eventoInfo) {
            return false;
        }

        $esp = (string) ($eventoInfo->especial_evento ?? '');

        return $esp === '2'
            || $esp === '7'
            || $esp === '9'
            || $esp === '4'
            || $esp === '5'
            || $esp === '1'
            || \App\Support\CalendarioLapsoSemanas::eventoModeloEsFestivo($eventoInfo);
    }

    protected function recalcularFinesLapso(): void
    {
        $calFin = $this->form->dia_fin_calendario_academico;
        if (!$calFin) {
            return;
        }

        $this->eventosRegistrados = array_values(array_filter(
            $this->eventosRegistrados,
            fn($ev) => !in_array($ev['especial_evento'] ?? '', ['3', '8', '10'])
        ));

        $eventosFinTemplates = \App\Models\Evento::whereIn('especial_evento', ['3', '8', '10'])
            ->where('estatus', '1')
            ->get()
            ->keyBy('especial_evento');

        if ($eventosFinTemplates->isEmpty()) {
            return;
        }

        $generarFin = function ($inicioEv, $semanas, $templateKey) use ($calFin, $eventosFinTemplates) {
            if ($semanas < 1 || !isset($eventosFinTemplates[$templateKey])) {
                return;
            }
            $template = $eventosFinTemplates[$templateKey];

            // Determinar si debemos incluir vacaciones colectivas (especial_evento = 1) en el conteo de semanas
            $incluirVacaciones = in_array($templateKey, ['3', '8']);

            $fechaFinAuto = \App\Support\CalendarioLapsoSemanas::fechaFinLapso(
                $inicioEv['inicio'],
                $semanas,
                $this->eventosRegistrados,
                $incluirVacaciones
            );

            if ($fechaFinAuto > $calFin) {
                return;
            }

            $colorFin = $template->codigo_color_evento ?? '';

            $this->eventosRegistrados[] = [
                'id' => (int) $template->id_evento,
                'inicio' => $fechaFinAuto,
                'fin' => $fechaFinAuto,
                'nombre_evento' => (string) $template->nombre_evento,
                'tipo' => (string) $template->tipo_evento,
                'color' => (string) $colorFin,
                'is_cantidad_dias_evento' => (bool) $template->is_cantidad_dias_evento,
                'cantidad_dias_evento' => $template->cantidad_dias_evento,
                'especial_evento' => $templateKey,
            ];
        };

        // Lapsos regulares (2 -> 3)
        $inicios = collect($this->eventosRegistrados)
            ->filter(fn($ev) => ($ev['especial_evento'] ?? '') === '2')
            ->sortBy('inicio')
            ->values();

        foreach ($inicios as $index => $inicioEv) {
            $semanas = $index === 0
                ? (int) $this->form->semana_lapso_uno_calendario_academico
                : (int) $this->form->semana_lapso_dos_calendario_academico;
            $generarFin($inicioEv, $semanas, '3');
        }

        // Lapso Académico Trayecto Inicial (7 -> 8)
        $iniciosIntro = collect($this->eventosRegistrados)
            ->filter(fn($ev) => ($ev['especial_evento'] ?? '') === '7')
            ->sortBy('inicio')
            ->values();

        foreach ($iniciosIntro as $index => $inicioEv) {
            $semanas = $index === 0
                ? (int) $this->form->semana_lapso_uno_introductorio_calendario_academico
                : (int) $this->form->semana_lapso_dos_introductorio_calendario_academico;
            $generarFin($inicioEv, $semanas, '8');
        }

        // Intensivo (9 -> 10)
        $iniciosIntensivo = collect($this->eventosRegistrados)
            ->filter(fn($ev) => ($ev['especial_evento'] ?? '') === '9')
            ->sortBy('inicio')
            ->values();

        foreach ($iniciosIntensivo as $inicioEv) {
            $semanas = (int) $this->form->semana_intensibo_introductorio_calendario_academico;
            $generarFin($inicioEv, $semanas, '10');
        }

        $this->actualizarMapaEventos();
    }

    public function actualizarMapaEventos()
    {
        $mapa = [];
        foreach ($this->eventosRegistrados as $ev) {
            $start = \Carbon\Carbon::parse($ev['inicio']);
            $end = \Carbon\Carbon::parse($ev['fin']);
            $tipo = $ev['tipo'] ?? '1';

            // Determinar si el evento fue asignado exclusivamente en un fin de semana
            $isTodoWeekend = true;
            $temp = clone $start;
            while ($temp->lte($end)) {
                if ($temp->dayOfWeekIso < 6) {
                    $isTodoWeekend = false;
                    break;
                }
                $temp->addDay();
            }

            $ignorarFinesDeSemana = !in_array($tipo, ['1', '2', '6']) && !$isTodoWeekend;

            $actual = clone $start;
            while ($actual->lte($end)) {
                $dayOfWeek = $actual->dayOfWeekIso;

                if ($ignorarFinesDeSemana && $dayOfWeek >= 6) {
                    $actual->addDay();
                    continue;
                }

                $fechaStr = $actual->format('Y-m-d');
                if (!isset($mapa[$fechaStr])) {
                    $mapa[$fechaStr] = [];
                }
                $mapa[$fechaStr][] = [
                    'id' => $ev['id'] ?? null,
                    'nombre_evento' => $ev['nombre_evento'] ?? $ev['nombre'] ?? 'Sin nombre',
                    'color' => $ev['color'] ?? '#333',
                    'inicio' => $ev['inicio'] ?? $fechaStr,
                    'fin' => $ev['fin'] ?? $fechaStr,
                    'tipo' => $tipo,
                    'especial_evento' => $ev['especial_evento'] ?? null
                ];
                $actual->addDay();
            }
        }
        $this->eventosPorFecha = $mapa;
    }

    public function removerEvento($index)
    {
        if (!isset($this->eventosRegistrados[$index])) {
            return;
        }

        $removido = $this->eventosRegistrados[$index];
        $idsFestivos = \App\Support\CalendarioLapsoSemanas::idsEventosFestivos($this->eventosRegistrados);
        $eraFestivo = \App\Support\CalendarioLapsoSemanas::registroEsFestivo($removido, $idsFestivos) || ($removido['especial_evento'] ?? '') === '1';
        $eraInicioLapso = in_array($removido['especial_evento'] ?? '', ['2', '7', '9']);

        unset($this->eventosRegistrados[$index]);
        $this->eventosRegistrados = array_values($this->eventosRegistrados);
        $this->actualizarMapaEventos();

        $hayInicios = collect($this->eventosRegistrados)->contains(
            fn($e) => in_array($e['especial_evento'] ?? '', ['2', '7', '9'])
        );

        if ($hayInicios && ($eraFestivo || $eraInicioLapso)) {
            $this->recalcularFinesLapso();
        }

        $this->guardarBorrador();
    }

    public function crearYAgregarEvento($inicio, $fin, $nombre, $tipo, $codigo_color_evento, $is_laborable, $is_repetible, $is_rango_dias, $rango_dias, $is_superponible = true)
    {
        // Validar usando el objeto Form
        try {
            $this->form->validarRangoEvento($inicio, $fin, $tipo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showAlert('error', $e->validator->errors()->first());
            return false;
        }

        if (in_array($tipo, ['1', '2', '6'])) {
            $is_laborable = false;
            $is_repetible = false;
        }

        // VALIDAR REGLA DE SUPERPOSICIÓN CON VACACIONES COLECTIVAS ANTES DE CREAR EL TEMPLATE
        $is_superponible_nuevo = in_array($tipo, ['1', '2', '6']) ? true : false;
        if (!$is_superponible_nuevo) {
            foreach ($this->eventosRegistrados as $evReg) {
                if (($evReg['especial_evento'] ?? '') === '1') {
                    if ($inicio <= $evReg['fin'] && $fin >= $evReg['inicio']) {
                        $this->showAlert('error', "El evento '{$nombre}' no es superponible y no puede registrarse en la misma fecha que las Vacaciones Colectivas.");
                        return false;
                    }
                }
            }
        }

        $this->form->isCreatingEvento = true;

        try {
            // Validar usando las reglas del formulario
            $validador = \Illuminate\Support\Facades\Validator::make(
                $this->form->all(),
                $this->form->rules(),
                $this->form->messages()
            );

            if ($validador->fails()) {
                $errores = $validador->errors()->all();
                $msg = "Error al crear el nuevo evento:\n\n• " . implode("\n• ", $errores);
                $this->showAlert('error', $msg);
                return false;
            }

            $eventoRepo = new EventoIndexRepo();
            $id_evento = $this->calendarioRepository->crearTemplate([
                'codigo_color_evento' => $codigo_color_evento,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'is_laborable' => $is_laborable,
                'is_repetible' => $is_repetible,
                'is_rango_dias' => $is_rango_dias,
                'rango_dias' => $rango_dias,
                'is_independiente' => $this->form->nuevoIsIndependiente,
                'is_superponible' => $is_superponible,
            ]);

            $colorHex = $codigo_color_evento ?: '#808080';

            $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();

            // Agregar al calendario (esto llamará a guardarBorrador)
            $this->agregarEvento($inicio, $fin, $id_evento, $nombre, $tipo, $colorHex);

            return true;
        } catch (Exception $e) {
            $this->js("alert('Error al crear el nuevo evento: " . addslashes($e->getMessage()) . "')");
            return false;
        } finally {
            $this->form->isCreatingEvento = false;
        }
    }

    protected function guardarBorrador()
    {
        if (!$this->id_calendario)
            return;

        try {
            $this->calendarioRepository->guardarBorrador([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                'semana_lapso_uno_calendario_academico' => $this->form->semana_lapso_uno_calendario_academico,
                'semana_lapso_dos_calendario_academico' => $this->form->semana_lapso_dos_calendario_academico,
                'semana_lapso_uno_introductorio_calendario_academico' => $this->form->semana_lapso_uno_introductorio_calendario_academico,
                'semana_lapso_dos_introductorio_calendario_academico' => $this->form->semana_lapso_dos_introductorio_calendario_academico,
                'semana_intensibo_introductorio_calendario_academico' => $this->form->semana_intensibo_introductorio_calendario_academico,
                'estatus' => '2' // Sigue en revisión
            ], $this->eventosRegistrados, $this->id_calendario);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando borrador en edición: ' . $e->getMessage());
        }
    }

    public function validarSeccionFechas()
    {
        $this->form->validarSeccionFechas();

        $mensajes = [];

        $lapsoUno = (int) $this->form->semana_lapso_uno_calendario_academico;
        if ($lapsoUno < 16) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico 1 con una cantidad inferior a 16 semanas?";
        } elseif ($lapsoUno > 18) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico 1 con una cantidad superior a 18 semanas?";
        }

        $lapsoDos = (int) $this->form->semana_lapso_dos_calendario_academico;
        if ($lapsoDos < 16) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico 2 con una cantidad inferior a 16 semanas?";
        } elseif ($lapsoDos > 18) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico 2 con una cantidad superior a 18 semanas?";
        }

        $inicialUno = (int) $this->form->semana_lapso_uno_introductorio_calendario_academico;
        if ($inicialUno < 12) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico Trayecto Inicial 1 con una cantidad inferior a 12 semanas?";
        } elseif ($inicialUno > 12) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico Trayecto Inicial 1 con una cantidad superior a 12 semanas?";
        }

        $inicialDos = (int) $this->form->semana_lapso_dos_introductorio_calendario_academico;
        if ($inicialDos < 12) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico Trayecto Inicial 2 con una cantidad inferior a 12 semanas?";
        } elseif ($inicialDos > 12) {
            $mensajes[] = "¿Está seguro de registrar el Lapso Académico Trayecto Inicial 2 con una cantidad superior a 12 semanas?";
        }

        $intensivo = (int) $this->form->semana_intensibo_introductorio_calendario_academico;
        if ($intensivo < 6) {
            $mensajes[] = "¿Está seguro de registrar las Semanas del curso Intensivo con una cantidad inferior a 6 semanas?";
        } elseif ($intensivo > 6) {
            $mensajes[] = "¿Está seguro de registrar las Semanas del curso Intensivo con una cantidad superior a 6 semanas?";
        }

        if (!empty($mensajes)) {
            $mensajeFinal = "";
            if (count($mensajes) > 1) {
                foreach ($mensajes as $i => $msg) {
                    $mensajeFinal .= "• " . $msg;
                    if ($i < count($mensajes) - 1) {
                        $mensajeFinal .= "\n\n";
                    }
                }
            } else {
                $mensajeFinal = $mensajes[0];
            }

            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => $mensajeFinal,
                'showCancelButton' => true,
                'cancelText' => 'Cancelar',
                'okText' => 'Continuar',
                'onOkEvent' => 'seccion-fechas-validada'
            ]);
            return;
        }

        $this->dispatch('seccion-fechas-validada');
    }

    public function aprobar($confirmadoAgosto = false, $confirmadoIrreversible = false)
    {
        if (!Gate::allows('cambiar-estatus-calendario')) {
            abort(403);
        }

        $this->resetErrorBag();
        $validacion = $this->form->validarFormularioCompleto($this->eventosRegistrados);

        if (!$validacion['valido']) {
            if ($this->getErrorBag()->hasAny(['form.dia_inicio_calendario_academico', 'form.dia_fin_calendario_academico'])) {
                $this->dispatch('abrir-seccion', section: 'fechas');
            } else {
                $this->dispatch('abrir-seccion', section: 'eventos');
            }

            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $validacion['errores']);
            $this->showAlert('error', $msg);
            return;
        }

        if (!$confirmadoAgosto) {
            $tieneVacacionesEnAgosto = false;
            $tieneIntensivoEnAgosto = false;

            foreach ($this->eventosRegistrados as $ev) {
                $fechaInicio = \Carbon\Carbon::parse($ev['dia_inicio_detalle_evento']);
                $fechaFin = \Carbon\Carbon::parse($ev['dia_fin_detalle_evento']);
                $cruzaAgosto = false;
                
                for ($d = $fechaInicio->copy(); $d->lte($fechaFin); $d->addDay()) {
                    if ($d->month === 8) {
                        $cruzaAgosto = true;
                        break;
                    }
                }

                if ($cruzaAgosto) {
                    if ($ev['especial_evento'] == 1) {
                        $tieneVacacionesEnAgosto = true;
                    }
                    if ($ev['especial_evento'] == 9 || $ev['especial_evento'] == 10) {
                        $tieneIntensivoEnAgosto = true;
                    }
                }
            }

            $mensajesAgosto = [];
            if (!$tieneVacacionesEnAgosto) {
                $mensajesAgosto[] = "¿Está seguro de guardar la planificación sin haber asignado días de vacaciones colectivas en agosto?";
            }
            if (!$tieneIntensivoEnAgosto) {
                $mensajesAgosto[] = "¿Está seguro de guardar el calendario sin haber asignado intensivos en agosto?";
            }

            if (!empty($mensajesAgosto)) {
                $mensajeFinal = "";
                if (count($mensajesAgosto) > 1) {
                    foreach ($mensajesAgosto as $i => $msg) {
                        $mensajeFinal .= "• " . $msg;
                        if ($i < count($mensajesAgosto) - 1) {
                            $mensajeFinal .= "\n\n";
                        }
                    }
                } else {
                    $mensajeFinal = $mensajesAgosto[0];
                }

                $this->dispatch('show-alert', [
                    'type' => 'warning',
                    'message' => $mensajeFinal,
                    'showCancelButton' => true,
                    'cancelText' => 'Cancelar',
                    'okText' => 'Continuar',
                    'onOkEvent' => 'confirmar-aprobacion-calendario'
                ]);
                return;
            }
        }

        if (!$confirmadoIrreversible) {
            $this->dispatch('show-alert', [
                'type' => 'warning',
                'message' => '¿Está seguro de Aprobar este calendario? Esta es una acción irreversible que afectará la planificación activa.',
                'showCancelButton' => true,
                'cancelText' => 'Cancelar',
                'okText' => 'Aprobar',
                'countdown' => 20,
                'onOkEvent' => 'confirmar-aprobacion-irreversible'
            ]);
            return;
        }

        try {
            DB::transaction(function () {
                // Verificar si ya existe un calendario activo (estatus 1)
                $calendarioActivo = DB::table('calendario_academico')
                    ->where('estatus', '1')
                    ->where('id_calendario_academico', '!=', $this->id_calendario)
                    ->first();

                $nuevoEstatus = $calendarioActivo ? '4' : '1';

                $this->calendarioRepository->actualizarEstatus($this->id_calendario, $nuevoEstatus, [
                    'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                    'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                    'semana_lapso_uno_calendario_academico' => $this->form->semana_lapso_uno_calendario_academico,
                    'semana_lapso_dos_calendario_academico' => $this->form->semana_lapso_dos_calendario_academico,
                    'semana_lapso_uno_introductorio_calendario_academico' => $this->form->semana_lapso_uno_introductorio_calendario_academico,
                    'semana_lapso_dos_introductorio_calendario_academico' => $this->form->semana_lapso_dos_introductorio_calendario_academico,
                    'semana_intensibo_introductorio_calendario_academico' => $this->form->semana_intensibo_introductorio_calendario_academico,
                ]);

                $this->calendarioRepository->sincronizarEventos($this->id_calendario, $this->eventosRegistrados);

                // Guardar el estatus resultante para usarlo en el mensaje
                session()->flash('calendario_nuevo_estatus', $nuevoEstatus);
            });

            $nuevoEstatus = session('calendario_nuevo_estatus');
            if ($nuevoEstatus === '4') {
                $this->showAlert('success', 'Calendario aprobado. Pasó a estatus "En Espera" hasta que finalice el calendario actual.', '/calendario/list');
            } else {
                $this->showAlert('success', 'Calendario aprobado y activado correctamente.', '/calendario/list');
            }
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al aprobar el calendario: ' . $e->getMessage());
        }
    }

    public function actualizar($confirmado = false)
    {
        if (!Gate::allows('cambiar-estatus-calendario')) {
            abort(403);
        }

        $this->resetErrorBag();
        $validacion = $this->form->validarFormularioCompleto($this->eventosRegistrados);

        if (!$validacion['valido']) {
            if ($this->getErrorBag()->hasAny(['form.dia_inicio_calendario_academico', 'form.dia_fin_calendario_academico'])) {
                $this->dispatch('abrir-seccion', section: 'fechas');
            } else {
                $this->dispatch('abrir-seccion', section: 'eventos');
            }

            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $validacion['errores']);
            $this->showAlert('error', $msg);
            return;
        }

        if (!$confirmado) {
            $tieneVacacionesEnAgosto = false;
            $tieneIntensivoEnAgosto = false;

            foreach ($this->eventosRegistrados as $ev) {
                $fechaInicio = \Carbon\Carbon::parse($ev['dia_inicio_detalle_evento']);
                $fechaFin = \Carbon\Carbon::parse($ev['dia_fin_detalle_evento']);
                $cruzaAgosto = false;
                
                for ($d = $fechaInicio->copy(); $d->lte($fechaFin); $d->addDay()) {
                    if ($d->month === 8) {
                        $cruzaAgosto = true;
                        break;
                    }
                }

                if ($cruzaAgosto) {
                    if ($ev['especial_evento'] == 1) {
                        $tieneVacacionesEnAgosto = true;
                    }
                    if ($ev['especial_evento'] == 9 || $ev['especial_evento'] == 10) {
                        $tieneIntensivoEnAgosto = true;
                    }
                }
            }

            $mensajesAgosto = [];
            if (!$tieneVacacionesEnAgosto) {
                $mensajesAgosto[] = "¿Está seguro de guardar la planificación sin haber asignado días de vacaciones colectivas en agosto?";
            }
            if (!$tieneIntensivoEnAgosto) {
                $mensajesAgosto[] = "¿Está seguro de guardar el calendario sin haber asignado intensivos en agosto?";
            }

            if (!empty($mensajesAgosto)) {
                $mensajeFinal = "";
                if (count($mensajesAgosto) > 1) {
                    foreach ($mensajesAgosto as $i => $msg) {
                        $mensajeFinal .= "• " . $msg;
                        if ($i < count($mensajesAgosto) - 1) {
                            $mensajeFinal .= "\n\n";
                        }
                    }
                } else {
                    $mensajeFinal = $mensajesAgosto[0];
                }

                $this->dispatch('show-alert', [
                    'type' => 'warning',
                    'message' => $mensajeFinal,
                    'showCancelButton' => true,
                    'cancelText' => 'Cancelar',
                    'okText' => 'Continuar',
                    'onOkEvent' => 'confirmar-actualizacion-calendario'
                ]);
                return;
            }
        }

        try {
            DB::transaction(function () {
                $this->calendarioRepository->actualizarEstatus($this->id_calendario, '2', [
                    'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                    'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                    'semana_lapso_uno_calendario_academico' => $this->form->semana_lapso_uno_calendario_academico,
                    'semana_lapso_dos_calendario_academico' => $this->form->semana_lapso_dos_calendario_academico,
                    'semana_lapso_uno_introductorio_calendario_academico' => $this->form->semana_lapso_uno_introductorio_calendario_academico,
                    'semana_lapso_dos_introductorio_calendario_academico' => $this->form->semana_lapso_dos_introductorio_calendario_academico,
                    'semana_intensibo_introductorio_calendario_academico' => $this->form->semana_intensibo_introductorio_calendario_academico,
                ]);

                $this->calendarioRepository->sincronizarEventos($this->id_calendario, $this->eventosRegistrados);
            });

            $this->showAlert('success', 'Calendario actualizado (guardado como revisión).', '/calendario/list');
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar el calendario: ' . $e->getMessage());
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function cancelar()
    {
        return redirect()->route('calendario.list');
    }

    #[\Livewire\Attributes\On('confirmar-aprobacion-calendario')]
    public function confirmarAprobacionAgosto()
    {
        $this->aprobar(true, false);
    }

    #[\Livewire\Attributes\On('confirmar-aprobacion-irreversible')]
    public function confirmarAprobacionIrreversible()
    {
        $this->aprobar(true, true);
    }

    #[\Livewire\Attributes\On('confirmar-actualizacion-calendario')]
    public function confirmarActualizacion()
    {
        $this->actualizar(true);
    }

    #[Computed]
    public function bibliotecaFiltrada()
    {
        $eventoRepo = new EventoIndexRepo();
        $biblioteca = $eventoRepo->obtenerBiblioteca();

        // Determinar el año seleccionado
        $targetYear = $this->selectedYearTemporal;
        if (!$targetYear && $this->form->dia_inicio_calendario_academico) {
            $targetYear = date('Y', strtotime($this->form->dia_inicio_calendario_academico));
        }
        if (!$targetYear) {
            $targetYear = date('Y');
        }

        // Obtener IDs y CONTEOS de eventos asignados EN TODO EL CALENDARIO ACTUAL
        $conteosAsignadosTotal = [];
        $idsAsignadosEsteAnio = [];
        foreach ($this->eventosRegistrados as $ev) {
            $idEv = $ev['id'] ?? null;
            if ($idEv) {
                $conteosAsignadosTotal[$idEv] = ($conteosAsignadosTotal[$idEv] ?? 0) + 1;

                $evStart = $ev['inicio'] ?? null;
                if ($evStart) {
                    $evYear = date('Y', strtotime($evStart));
                    if ((int) $evYear === (int) $targetYear) {
                        $idsAsignadosEsteAnio[] = $idEv;
                    }
                }
            }
        }
        $idsAsignadosEsteAnio = array_filter(array_unique($idsAsignadosEsteAnio));

        return $biblioteca->filter(function ($evento) use ($idsAsignadosEsteAnio, $conteosAsignadosTotal) {
            $especial = $evento->especial_evento ?? null;
            $id = $evento->id_evento;

            // Lapsos Académicos (2, 3) e Introductorios (7, 8) -> Hasta 2 veces en el calendario general
            if (in_array($especial, ['2', '3', '7', '8'])) {
                return ($conteosAsignadosTotal[$id] ?? 0) < 2;
            }

            // Curso Intensivo (9, 10) -> Hasta 1 vez en el calendario general
            if (in_array($especial, ['9', '10'])) {
                return ($conteosAsignadosTotal[$id] ?? 0) < 1;
            }

            // Si el evento es repetible, siempre aparece.
            // Si NO es repetible, solo aparece si NO ha sido asignado aún en este año.
            return $evento->is_repetible_evento || !in_array($id, $idsAsignadosEsteAnio);
        })->values();
    }

    #[Computed]
    public function vacacionesContador()
    {
        $repo = new \App\Repositories\Calendario\CalendarioUpdateRepo();
        $eventoVacaciones = $repo->obtenerEventoVacacionesActivo();
        if (!$eventoVacaciones) {
            return null;
        }

        // Determinar el año seleccionado
        $targetYear = $this->selectedYearTemporal;
        if (!$targetYear && $this->form->dia_inicio_calendario_academico) {
            $targetYear = date('Y', strtotime($this->form->dia_inicio_calendario_academico));
        }
        if (!$targetYear) {
            $targetYear = date('Y');
        }

        // Sumar días de vacaciones colectivas asignados en el período actual para este año
        $diasActuales = 0;
        foreach ($this->eventosRegistrados as $reg) {
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
                    if ($date->format('Y') == $targetYear) {
                        $diasActuales++;
                    }
                }
            }
        }

        // Según el requerimiento: Solo se deben contar los días de vacaciones actuales del calendario específico
        $diasEnOtrosCalendarios = 0;

        $totalAsignados = $diasActuales + $diasEnOtrosCalendarios;
        $cantidadRequerida = $eventoVacaciones->cantidad_dias_evento ?? 60;
        $faltantes = $cantidadRequerida - $totalAsignados;

        return [
            'anio' => $targetYear,
            'requeridos' => $cantidadRequerida,
            'asignados_actual' => $diasActuales,
            'asignados_otros' => $diasEnOtrosCalendarios,
            'total_assignados' => $totalAsignados,
            'faltantes' => max(0, $faltantes),
            'excedidos' => $faltantes < 0 ? abs($faltantes) : 0,
        ];
    }

    public function render()
    {
        return view('livewire.pages.calendario.editar-calendario', [
            'bibliotecaFiltrada' => $this->bibliotecaFiltrada()
        ]);
    }
}

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
    public $colores = [];
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
                'is_rango_dias_evento' => (bool) ($ev->is_rango_dias_evento ?? false),
                'rango_dias_evento' => $ev->rango_dias_evento ?? null,
            ];
        }

        // Cargar la biblioteca de eventos (templates)
        $eventoRepo = new EventoIndexRepo();
        $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
        $this->cargarColoresDisponibles();
        $this->actualizarMapaEventos();
    }

    public function cargarColoresDisponibles()
    {
        $eventoRepo = new EventoIndexRepo();
        $this->colores = $eventoRepo->obtenerColoresDisponibles();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Actualización dinámica de la fecha de fin
        if ($propertyName === 'form.dia_inicio_calendario_academico') {
            $this->calcularFechaFin();
        }

        if ($propertyName == 'form.dia_inicio_calendario_academico' || $propertyName == 'form.dia_fin_calendario_academico') {
            $this->guardarBorrador();
        }

        if ($propertyName === 'form.nuevoTipo') {
            if ($this->form->nuevoTipo == '1' || $this->form->nuevoTipo == '2') {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = false;
                $this->form->nuevoIsIndependiente = true;
            } else {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = false;
                $this->form->nuevoIsIndependiente = false;
            }
        }
    }

    public function calcularFechaFin()
    {
        if (!$this->form->dia_inicio_calendario_academico) {
            return;
        }

        try {
            $inicio = \Carbon\Carbon::parse($this->form->dia_inicio_calendario_academico);
            
            // Semestral por defecto: 18 semanas (Semana 1 a Semana 18 el mismo día) = 17 semanas de diferencia
            $fin = $inicio->copy()->addWeeks(17);

            $this->form->dia_fin_calendario_academico = $fin->format('Y-m-d');
            $this->guardarBorrador();
        } catch (\Exception $e) {
            // Error silencioso si la fecha no es válida aún
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
                $nombre = (string)$info->nombre_evento;
                $color = (string)$info->codigo_color;
                $tipo = (string)$info->tipo_evento;
            }
        } else {
            $nombre = (string)$eventoInfo->nombre_evento;
            // Intentar obtener el color desde la relación o fallback
            $color = (string)($eventoInfo->color_rel ? $eventoInfo->color_rel->codigo_color : $color);
            $tipo = (string)$eventoInfo->id_tipo_evento;
        }

        // Analizar si el rango contiene fines de semana
        $start = new \DateTime($inicio);
        $end = new \DateTime($fin);
        $contieneWeekend = false;
        $todoEsWeekend = true;

        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));

        foreach ($period as $date) {
            $dayOfWeek = (int)$date->format('N'); // 1 (Lunes) a 7 (Domingo)
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
        if (in_array($tipo, ['1', '2']) || $todoEsWeekend) {
            $subrangos[] = ['inicio' => $inicio, 'fin' => $fin];
        } else {
            // Otros eventos: dividimos omitiendo los fines de semana
            $currentStart = null;
            $currentEnd = null;

            foreach ($period as $date) {
                $dayOfWeek = (int)$date->format('N');
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

        foreach ($subrangos as $sub) {
            $nuevoEvento = [
                'id' => (int)$id_evento,
                'inicio' => (string)$sub['inicio'],
                'fin' => (string)$sub['fin'],
                'nombre_evento' => (string)$nombre,
                'tipo' => (string)$tipo,
                'color' => (string)$color,
                'is_rango_dias_evento' => $eventoInfo ? (bool)$eventoInfo->is_rango_dias_evento : false,
                'rango_dias_evento' => $eventoInfo ? $eventoInfo->rango_dias_evento : null,
            ];

            $this->eventosRegistrados[] = $nuevoEvento;
        }

        $this->actualizarMapaEventos();
        $this->guardarBorrador();
    }

    /**
     * Organiza los eventos en un mapa indexado por fecha
     */
    public function actualizarMapaEventos()
    {
        $mapa = [];
        foreach ($this->eventosRegistrados as $ev) {
            $actual = \Carbon\Carbon::parse($ev['inicio']);
            $fin = \Carbon\Carbon::parse($ev['fin']);

            while ($actual->lte($fin)) {
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
                    'tipo' => $ev['tipo'] ?? '1'
                ];
                $actual->addDay();
            }
        }
        $this->eventosPorFecha = $mapa;
    }

    public function removerEvento($index)
    {
        if (isset($this->eventosRegistrados[$index])) {
            unset($this->eventosRegistrados[$index]);
            $this->eventosRegistrados = array_values($this->eventosRegistrados);
            $this->guardarBorrador();
        }
    }

    public function crearYAgregarEvento($inicio, $fin, $nombre, $tipo, $id_color, $is_laborable, $is_repetible, $is_rango_dias, $rango_dias)
    {
        // Validar usando el objeto Form
        try {
            $this->form->validarRangoEvento($inicio, $fin, $tipo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showAlert('error', $e->validator->errors()->first());
            return false;
        }

        if ($tipo == '1' || $tipo == '2') {
            $is_laborable = false;
            $is_repetible = false;
        }

        try {
            $eventoRepo = new EventoIndexRepo();
            $id_evento = $this->calendarioRepository->crearTemplate([
                'id_color' => $id_color,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'is_laborable' => $is_laborable,
                'is_repetible' => $is_repetible,
                'is_rango_dias' => $is_rango_dias,
                'rango_dias' => $rango_dias,
                'is_independiente' => $this->form->nuevoIsIndependiente,
            ]);

            $colorObj = $eventoRepo->obtenerColorPorId($id_color);
            $colorHex = $colorObj ? $colorObj->codigo_color : '#808080';

            $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
            $this->cargarColoresDisponibles();

            // Agregar al calendario (esto llamará a guardarBorrador)
            $this->agregarEvento($inicio, $fin, $id_evento, $nombre, $tipo, $colorHex);

            return true;
        } catch (Exception $e) {
            $this->js("alert('Error al crear el nuevo evento: " . addslashes($e->getMessage()) . "')");
            return false;
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
                'estatus' => '2' // Sigue en revisión
            ], $this->eventosRegistrados, $this->id_calendario);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando borrador en edición: ' . $e->getMessage());
        }
    }

    public function validarSeccionFechas()
    {
        $this->form->validarSeccionFechas();
        $this->dispatch('seccion-fechas-validada');
    }

    public function aprobar()
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

        try {
            DB::transaction(function () {
                $this->calendarioRepository->actualizarEstatus($this->id_calendario, '1', [
                    'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                    'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                ]);

                $this->calendarioRepository->sincronizarEventos($this->id_calendario, $this->eventosRegistrados);
            });

            $this->showAlert('success', 'Calendario aprobado y activado correctamente.', '/calendario/list');
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al aprobar el calendario: ' . $e->getMessage());
        }
    }

    public function actualizar()
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

        try {
            DB::transaction(function () {
                $this->calendarioRepository->actualizarEstatus($this->id_calendario, '2', [
                    'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                    'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
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

        // Obtener IDs de eventos asignados EN EL AÑO SELECCIONADO del calendario actual
        $idsAsignadosEsteAnio = [];
        foreach ($this->eventosRegistrados as $ev) {
            $evStart = $ev['inicio'] ?? null;
            if ($evStart) {
                $evYear = date('Y', strtotime($evStart));
                if ((int)$evYear === (int)$targetYear) {
                    $idsAsignadosEsteAnio[] = $ev['id'] ?? null;
                }
            }
        }
        $idsAsignadosEsteAnio = array_filter(array_unique($idsAsignadosEsteAnio));

        return $biblioteca->filter(function ($evento) use ($idsAsignadosEsteAnio) {
            // Si es un evento especial de tipo 2 (Inicio) o 3 (Fin) y ya está asignado en este año, no lo mostramos
            $especial = $evento->especial_evento ?? null;
            if (in_array($especial, ['2', '3']) && in_array($evento->id_evento, $idsAsignadosEsteAnio)) {
                return false;
            }

            // Si el evento es repetible, siempre aparece.
            // Si NO es repetible, solo aparece si NO ha sido asignado aún en este año.
            return $evento->is_repetible_evento || !in_array($evento->id_evento, $idsAsignadosEsteAnio);
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
                
                $interval = new \DateInterval('P1D');
                $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
                foreach ($period as $date) {
                    if ($date->format('Y') == $targetYear) {
                        $diasActuales++;
                    }
                }
            }
        }

        // Sumar días ya asignados en otros calendarios para este año
        $excluirId = $this->id_calendario;
        $diasEnOtrosCalendarios = $repo->obtenerDiasVacacionesEnOtrosCalendarios($eventoVacaciones->id_evento, $targetYear, $excluirId);

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

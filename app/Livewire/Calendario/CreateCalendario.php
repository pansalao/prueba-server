<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Livewire\Forms\Calendario\CreateCalendarioForm;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class CreateCalendario extends Component
{
    public CreateCalendarioForm $form;
    protected $calendarioRepository;

    public $eventosRegistrados = [];
    public $eventosPorFecha = []; // Nuevo: Mapa de eventos agrupados por fecha
    public $bibliotecaEventos = [];
    public $currentYear;

    public $id_calendario_borrador = null;
    public $colores = [];

    public function boot()
    {
        $this->calendarioRepository = new CalendarioCreateRepo();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Si cambia el inicio, recalculamos el fin automáticamente
        if ($propertyName === 'form.dia_inicio_calendario_academico') {
            $this->calcularFechaFin();
        }

        if ($propertyName == 'form.dia_inicio_calendario_academico' || $propertyName == 'form.dia_fin_calendario_academico') {
            $this->filtrarEventosFueraDeRango();
            $this->guardarBorrador();
        }

        if ($propertyName === 'form.nuevoTipo') {
            if ($this->form->nuevoTipo == '1' || $this->form->nuevoTipo == '2') {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = false;
            } else {
                $this->form->nuevoLaborable = false;
                $this->form->nuevoRepetible = false;
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

    public function mount($id = null)
    {
        $this->currentYear = date('Y');

        if ($id) {
            $this->id_calendario_borrador = $id;
            $calendario = $this->calendarioRepository->obtenerPorId($id);

            if ($calendario) {
                $this->form->dia_inicio_calendario_academico = $calendario->dia_inicio_calendario_academico;
                $this->form->dia_fin_calendario_academico = $calendario->dia_fin_calendario_academico;

                // Cargar eventos registrados desde el repositorio
                $eventos = $this->calendarioRepository->obtenerEventosDetalle($id);

                foreach ($eventos as $ev) {
                    $this->eventosRegistrados[] = [
                        'id' => $ev->id,
                        'inicio' => $ev->inicio,
                        'fin' => $ev->fin,
                        'nombre' => $ev->nombre,
                        'tipo' => $ev->tipo,
                        'color' => $ev->color,
                    ];
                }
                $this->actualizarMapaEventos();
            }
        }

        // Cargar la biblioteca de eventos (templates)
        $eventoRepo = new EventoIndexRepo();
        $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
        $this->cargarColoresDisponibles();
    }

    public function cargarColoresDisponibles()
    {
        $eventoRepo = new EventoIndexRepo();
        $this->colores = $eventoRepo->obtenerColoresDisponibles();
    }

    public function agregarEvento($inicio, $fin, $id_evento, $nombre = null, $tipo = null, $color = null)
    {
        $eventoInfo = \App\Models\Evento::find($id_evento);
        
        // Siempre buscar info fresca de la base de datos para evitar corrupción
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

        $nuevoEvento = [
            'id' => (int)$id_evento,
            'inicio' => (string)$inicio,
            'fin' => (string)$fin,
            'nombre_evento' => (string)$nombre,
            'tipo' => (string)$tipo,
            'color' => (string)$color,
            'is_rango_dias_evento' => $eventoInfo ? (bool)$eventoInfo->is_rango_dias_evento : false,
            'rango_dias_evento' => $eventoInfo ? $eventoInfo->rango_dias_evento : null,
        ];

        $this->eventosRegistrados[] = $nuevoEvento;
        $this->actualizarMapaEventos();
        $this->guardarBorrador();
    }

    /**
     * Organiza los eventos en un mapa indexado por fecha para que Alpine no tenga que filtrar
     */
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
            $this->filtrarEventosFueraDeRango();
        } catch (\Exception $e) {
            // Error silencioso si la fecha no es válida aún
        }
    }

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
            $this->actualizarMapaEventos();
            $this->guardarBorrador();
        }
    }

    public function crearYAgregarEvento($inicio, $fin, $nombre, $tipo, $id_color, $is_laborable, $is_repetible, $is_rango_dias, $rango_dias)
    {
        if ($tipo == '1' || $tipo == '2') {
            $is_laborable = false;
            $is_repetible = false;
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
                'id_color' => $id_color,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'is_laborable' => $is_laborable,
                'is_repetible' => $is_repetible,
                'is_rango_dias' => $is_rango_dias,
                'rango_dias' => $rango_dias,
            ]);

            $colorObj = $eventoRepo->obtenerColorPorId($id_color);
            $colorHex = $colorObj ? $colorObj->codigo_color : '#808080';

            $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
            $this->cargarColoresDisponibles();

            // Agregar al calendario
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
        // Solo guardamos si tenemos al menos una fecha
        if (!$this->form->dia_inicio_calendario_academico && !$this->form->dia_fin_calendario_academico) {
            return;
        }

        try {
            $this->id_calendario_borrador = $this->calendarioRepository->guardarBorrador([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
            ], $this->eventosRegistrados, $this->id_calendario_borrador);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando borrador: ' . $e->getMessage());
        }
    }

    public function validarSeccionFechas()
    {
        $this->form->validarSeccionFechas();
        $this->dispatch('seccion-fechas-validada');
    }

    public function save()
    {
        if (!Gate::allows('crear-calendario')) {
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
            $id = $this->calendarioRepository->crearConEventos([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
            ], $this->eventosRegistrados, $this->id_calendario_borrador);

            if ($id) {
                $this->showAlert('success', 'Calendario guardado exitosamente.', '/calendario/list');
            } else {
                $this->showAlert('error', 'No se pudo guardar el calendario.');
            }
        } catch (Exception $e) {
            $this->showAlert('error', $e->getMessage());
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
        $idsAsignados = collect($this->eventosRegistrados)->pluck('id')->filter()->toArray();

        return $biblioteca->filter(function ($evento) use ($idsAsignados) {
            // Si es un evento especial de tipo 2 (Inicio) o 3 (Fin) y ya está asignado, no lo mostramos
            $especial = $evento->especial_evento ?? null;
            if (in_array($especial, ['2', '3']) && in_array($evento->id_evento, $idsAsignados)) {
                return false;
            }

            // Si el evento es repetible, siempre aparece.
            // Si NO es repetible, solo aparece si NO ha sido asignado aún.
            return $evento->is_repetible_evento || !in_array($evento->id_evento, $idsAsignados);
        })->values();
    }

    public function render()
    {
        return view('livewire.pages.calendario.create-calendario', [
            'bibliotecaFiltrada' => $this->bibliotecaFiltrada()
        ]);
    }
}

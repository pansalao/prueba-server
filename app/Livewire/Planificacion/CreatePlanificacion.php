<?php

namespace App\Livewire\Planificacion;

use App\Repositories\Planificacion\PlanificacionCreateRepo;
use Illuminate\Support\{Collection, Facades\Auth, Facades\Gate, Str};
use Livewire\Component;
use Carbon\Carbon;

class CreatePlanificacion extends Component
{
    public $docente_id, $docenteNombre, $proposito;
    public Collection $tecnica, $recursosMaestros, $evaluaciones, $bibliografiasMaestras, $asignaciones;
    public \App\Livewire\Forms\Planificacion\CreatePlanificacionForm $form;
    public array $temasPorUnidad = [];
    protected $planificacionRepository;

    // public array $bibliografias = [['bibliografia_id' => '']]; // Moved to unit level

    public Collection $contenidosPorTema;
    public Collection $todosLosContenidos;
    public Collection $todosLosObjetivos;

    public $formasParticipacion = [];

    // Modal properties
    public $showObjetivoModal = false;
    public $newObjetivoNombre = '';
    public $selectedTemaIdForObjetivo = null;

    protected $listeners = [
        'itemCreated' => 'refreshMasterLists',
    ];

    public function __construct()
    {
        $this->planificacionRepository = new PlanificacionCreateRepo();
    }

    public function mount()
    {
        $this->docente_id = Auth::id();
        // Inicializar colecciones vacías para evitar errores de null
        $this->asignaciones = collect();
        $this->temasPorUnidad = [];
        $this->contenidosPorTema = collect();
        $this->todosLosContenidos = collect();
        $this->todosLosObjetivos = collect();

        $this->loadInitialData();
        $this->verifyDocenteRole();
        $this->inicializarUnidades();
    }

    public function updatedFormIdProfesorAsignado($value)
    {
        if ($value) {
            // Buscar la asignación seleccionada para obtener el ID de la unidad curricular
            $asignacion = $this->asignaciones->firstWhere('id_detalle_profesor_asignado', $value);

            if ($asignacion) {
                // Obtener ID de unidad y sección desde la base de datos para asegurar integridad
                $detalle = $this->planificacionRepository->getDetalleProfesorAsignado($value);
                if ($detalle) {
                    // 1. Cargar TEMAS agrupados por unidad_tema (1, 2, 3, 4)
                    $todosLosTemas = $this->planificacionRepository->select_temas_por_unidad($detalle->id_unidad_curricular);

                    $this->temasPorUnidad = [];
                    foreach (range(1, 4) as $num) {
                        $this->temasPorUnidad[$num] = $todosLosTemas->where('unidad_tema', (string) $num)->values();
                    }

                    // 2. Cargar TODOS los CONTENIDOS de la unidad curricular
                    // Se usarán para filtrar opciones cuando se seleccione un tema
                    $this->todosLosContenidos = $this->planificacionRepository->select_contenidos($detalle->id_unidad_curricular);

                    // 3. Cargar TODOS los OBJETIVOS de la unidad curricular
                    $this->todosLosObjetivos = $this->planificacionRepository->select_objetivos($detalle->id_unidad_curricular);

                    // Obtener propósito de la unidad curricular
                    $unidad = $this->planificacionRepository->getUnidadCurricular($detalle->id_unidad_curricular);
                    if ($unidad) {
                        $this->proposito = $unidad->proposito_unidad_curricular;
                    }
                }
            }
        } else {
            $this->temasPorUnidad = [];
            $this->todosLosContenidos = collect();
            $this->todosLosObjetivos = collect();
            $this->proposito = '';
        }

        // Reiniciar los contenidos seleccionados en las unidades porque cambiaron las opciones disponibles
        $this->inicializarUnidades();
    }

    protected function loadInitialData()
    {
        $this->tecnica = $this->planificacionRepository->select_tecnica();
        $this->evaluaciones = $this->planificacionRepository->select_evaluaciones();
        $this->recursosMaestros = $this->planificacionRepository->select_recursos();
        $this->bibliografiasMaestras = $this->planificacionRepository->select_bibliografias();

        // Cargar asignaciones: Si tiene permiso de edición (como un coordinador) ve todas, si es docente solo las suyas
        if (Gate::allows('editar-planificacion')) {
            $this->asignaciones = $this->planificacionRepository->getAsignacionesDocente();
        } else {
            $this->asignaciones = $this->planificacionRepository->getAsignacionesDocente($this->docente_id);
        }
    }

    public function refreshMasterLists($data)
    {
        switch ($data['tableName']) {
            case 'recurso':
                $this->recursosMaestros = $this->planificacionRepository->select_recursos();
                break;
            case 'tecnica':
                $this->tecnica = $this->planificacionRepository->select_tecnica();
                break;
            case 'evaluacion':
                $this->evaluaciones = $this->planificacionRepository->select_evaluaciones();
                break;
            case 'bibliografia':
                $this->bibliografiasMaestras = $this->planificacionRepository->select_bibliografias();
                break;
        }
    }

    protected function verifyDocenteRole()
    {
        // Allow based on permissions (crear-planificacion or editar-planificacion)
        if (Auth::check() && (Gate::allows('crear-planificacion') || Gate::allows('editar-planificacion'))) {
            $this->docenteNombre = Auth::user()->name . ' ' . Auth::user()->apellido;
        } else {
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Acceso denegado.']);
        }
    }

    protected function inicializarUnidades()
    {
        foreach (range(0, 3) as $index) {
            $this->form->unidades[$index] = $this->createUnidadTemplate($index + 1);
        }
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    protected function createUnidadTemplate($numero)
    {
        return [
            'numero' => $numero,
            'objetivos' => [
                [
                    'tema_id' => '',
                    'objetivo_id' => '',
                    'contenidos' => [['contenido_id' => '']]
                ]
            ],
            'estrategias' => [['tema_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]]],
            'evaluaciones' => [['fecha_evaluacion' => '', 'evaluacion_id' => '', 'ponderacion' => 5, 'tecnica_id' => '', 'forma_participacion' => '', 'integrantes' => null]],
            'bibliografias' => [['bibliografia_id' => '']],
            'indicadores_logro' => ''
        ];
    }

    public function addItem($unidadIndex, $arrayName, $parentIndex = null)
    {
        if ($arrayName === 'bibliografias') {
            // Add bibliography to unit
            $this->form->unidades[$unidadIndex]['bibliografias'][] = ['bibliografia_id' => ''];
        } elseif ($arrayName === 'objetivos') {
            // Add new objective block
            $this->form->unidades[$unidadIndex]['objetivos'][] = [
                'tema_id' => '',
                'objetivo_id' => '',
                'contenidos' => [['contenido_id' => '']]
            ];
        } elseif ($arrayName === 'contenidos') {
            // Add content to specific objective
            $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][] = ['contenido_id' => ''];

        } elseif ($arrayName === 'estrategia_recursos') {
            // Add resource to specific strategy
            // $parentIndex is the strategy index
            $this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][] = ['recurso_id' => ''];

        } else {
            // Fallback for evaluations
            $defaultTemplates = [
                'evaluaciones' => [
                    'fecha_evaluacion' => '',
                    'evaluacion_id' => '',
                    'ponderacion' => 0,
                    'tecnica_id' => '',
                    'forma_participacion' => '',
                    'integrantes' => null
                ],
                'estrategias' => ['tema_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]],
                // 'bibliografias' => ['bibliografia_id' => ''] // This was for global, now handled above
            ];
            if (isset($defaultTemplates[$arrayName])) {
                $this->form->unidades[$unidadIndex][$arrayName][] = $defaultTemplates[$arrayName];
            }
        }
    }

    public function removeItem($unidadIndex, $arrayName, $itemIndex, $parentIndex = null)
    {
        if ($arrayName === 'bibliografias') {
            if (isset($this->form->unidades[$unidadIndex]['bibliografias'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['bibliografias'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['bibliografias'] = array_values($this->form->unidades[$unidadIndex]['bibliografias']);
            }
        } elseif ($arrayName === 'objetivos') {
            // Remove objective block
            if (isset($this->form->unidades[$unidadIndex]['objetivos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['objetivos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['objetivos'] = array_values($this->form->unidades[$unidadIndex]['objetivos']);
            }
        } elseif ($arrayName === 'contenidos') {
            // Remove content from objective
            if (isset($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'] = array_values($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos']);
            }
        } elseif ($arrayName === 'estrategia_recursos') {
            // Remove resource from strategy
            if (isset($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'] = array_values($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos']);
            }
        } else {
            // Remove resource, evaluation, or strategy
            if (isset($this->form->unidades[$unidadIndex][$arrayName][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex][$arrayName][$itemIndex]);
                $this->form->unidades[$unidadIndex][$arrayName] = array_values($this->form->unidades[$unidadIndex][$arrayName]);
            }
        }
    }

    public function render()
    {
        return view('livewire.pages.planificacion.create-planificacion', [
            'weekDays' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'], // No longer used but kept for compatibility
            'timeSlots' => $this->generateTimeSlots()
        ]);
    }

    protected function generateTimeSlots()
    {
        return collect(range(8, 18))->map(fn($h) => sprintf('%02d:00', $h));
    }

    public function savePlanificacion()
    {
        $this->form->validate();

        try {
            $this->planificacionRepository->savePlanificacionTransaccion(
                $this->form->id_profesor_asignado,
                $this->form->unidades
            );

            $this->dispatch('mostrar-mensaje', ['tipo' => 'exitoso', 'mensaje' => 'Planificación guardada correctamente.']);
            $this->form->reset(['unidades', 'id_profesor_asignado']);
            $this->inicializarUnidades();
        } catch (\Exception $e) {
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Error al guardar la planificación: ' . $e->getMessage()]);
        }
    }
    public function openObjetivoModal($temaId)
    {
        if (!$temaId || $temaId === '') {
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Debe seleccionar un tema primero.']);
            return;
        }
        $this->selectedTemaIdForObjetivo = $temaId;
        $this->newObjetivoNombre = '';
        $this->showObjetivoModal = true;
    }

    public function closeObjetivoModal()
    {
        $this->showObjetivoModal = false;
        $this->reset(['newObjetivoNombre', 'selectedTemaIdForObjetivo']);
    }

    public function saveObjetivo()
    {
        $this->validate([
            'newObjetivoNombre' => 'required|min:3|max:255',
            'selectedTemaIdForObjetivo' => 'required|exists:tema_unidad,id_tema_unidad',
        ]);

        try {
            $this->planificacionRepository->saveNuevoObjetivo($this->newObjetivoNombre, $this->selectedTemaIdForObjetivo);

            // Recargar objetivos
            $detalle = $this->planificacionRepository->getDetalleProfesorAsignado($this->form->id_profesor_asignado);
            if ($detalle) {
                // Refresh objectives list
                $this->todosLosObjetivos = $this->planificacionRepository->select_objetivos($detalle->id_unidad_curricular);
            }

            $this->dispatch('mostrar-mensaje', ['tipo' => 'exitoso', 'mensaje' => 'Objetivo creado correctamente.']);
            $this->closeObjetivoModal();
        } catch (\Exception $e) {
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Error al guardar el objetivo: ' . $e->getMessage()]);
        }
    }
}

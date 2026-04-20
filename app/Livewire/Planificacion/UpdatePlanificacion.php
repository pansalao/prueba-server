<?php

namespace App\Livewire\Planificacion;

use App\Repositories\Planificacion\PlanificacionIndexRepo;
use App\Repositories\Planificacion\PlanificacionCreateRepo;
use App\Repositories\Planificacion\PlanificacionEditRepo;
use App\Repositories\Planificacion\PlanificacionViewRepo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Livewire\Forms\Planificacion\UpdatePlanificacionForm;


class UpdatePlanificacion extends Component
{

    protected $planificacionIndexRepo;
    protected $planificacionCreateRepo;
    protected $planificacionEditRepo;
    protected $planificacionViewRepo;

    // Datos principales de la planificación (NO EDITABLES, solo se muestran)
    public $planificacionId;
    public $docente_id;
    public $id_lapso_academico;
    public $docente_nombre;
    public $docente_apellido;
    public $cedula;
    public $nombre_unidad_curricular;
    public $nombre_seccion;
    public $nombre_lapso;
    public $nombre_malla;
    public $docente_rol;

    // Datos editables que vienen del formulario
    public UpdatePlanificacionForm $form;
    public $cortes = []; // Mantener temporalmente para la carga inicial, luego mover


    // Propiedades para listados de opciones
    public $recursosDisponibles = [];
    public $estrategiasDisponibles = [];
    public $contenidosDisponibles = [];
    public $indicadoresDisponibles = [];
    public $evaluacionesDisponibles = [];
    public $tecnicaDisponibles = [];
    public $bibliografiasDisponibles = [];

    // Fechas de lapso para validación
    public $id_unidad_curricular;
    public $lapso_fecha_inicio;
    public $lapso_fecha_fin;

    public $mostrarDetallesUnidad = false;

    public function __construct()
    {
        $this->planificacionIndexRepo = new PlanificacionIndexRepo();
        $this->planificacionCreateRepo = new PlanificacionCreateRepo();
        $this->planificacionEditRepo = new PlanificacionEditRepo();
        $this->planificacionViewRepo = new PlanificacionViewRepo();
    }

    public function mount($planificacionId)
    {
        $this->planificacionId = $planificacionId;

        // Autorización y carga de datos inicial de la planificación usando el repositorio
        $planificacion = $this->planificacionViewRepo->getDetallesPlanificacion($planificacionId);

        if (!$planificacion) {
            session()->flash('error', 'Planificación no encontrada.');
            return redirect()->to('/planificacion/listar');
        }

        // Acceder a 'docente_id' que ahora viene del array principal
        $this->docente_id = $planificacion['docente_id'];

        if (Auth::id() !== $this->docente_id && Gate::denies('editar-planificacion')) {
            abort(403, 'No tienes permiso para editar esta planificación.');
        }

        // Cargar las opciones estáticas primero
        $this->loadDropdownOptions();

        // Asignar datos de la planificación desde el array devuelto por el repositorio
        $this->docente_nombre = $planificacion['docente_nombre'];
        $this->docente_apellido = $planificacion['docente_apellido'];
        $this->cedula = $planificacion['cedula'];
        $this->id_unidad_curricular = $planificacion['id_unidad_curricular'];
        $this->lapso_fecha_inicio = $planificacion['lapso_fecha_inicio'];
        $this->lapso_fecha_fin = $planificacion['lapso_fecha_fin'];
        $this->id_lapso_academico = $planificacion['id_lapso_academico'];
        $this->nombre_unidad_curricular = $planificacion['nombre_unidad_curricular'];
        $this->nombre_seccion = $planificacion['nombre_seccion'];
        $this->nombre_lapso = $planificacion['nombre_lapso'];
        $this->docente_rol = $planificacion['docente_rol'] ?? 'Docente';

        // Obtener Malla (usando el id_profesor_asignado que está en la DB o el id_detalle_profesor_asignado)
        // El Repo viewRepo ya debería darnos esto, pero si no, lo buscamos
        $malla = $this->planificacionCreateRepo->getMallaByAsignacion($planificacion['id_detalle_profesor_asignado'] ?? null);
        $this->nombre_malla = $malla ? $malla->mal_nombre : 'No especificada';

        // Cargar contenidos disponibles filtrados por unidad
        $this->loadContenidosUnidad();

        // Cargar detalles dinámicos (cortes, bibliografías) desde el array del repositorio
        $this->form->bibliografias = collect($planificacion['bibliografias'])
            ->map(fn($item) => ['bibliografia_id' => $item['bibliografia_id']])
            ->toArray();

        $this->form->cortes = collect($planificacion['cortes'])
            ->map(function ($corte) {
                // Mapear los recursos
                $recursos = collect($corte['recursos'])
                    ->map(fn($r) => ['recurso_id' => $r['recurso_id']])
                    ->toArray();

                // Mapear las estrategias
                $estrategias = collect($corte['estrategias'])
                    ->map(fn($e) => ['tema_id' => $e['tema_id'], 'actividad' => $e['actividad'] ?? ''])
                    ->toArray();

                // Mapear contenidos
                $contenidos = collect($corte['contenidos'])
                    ->map(function ($cont) {
                    return [
                        'contenido_id' => $cont['contenido_id'],
                    ];
                })
                    ->toArray();

                // Mapear evaluaciones
                $evaluaciones = collect($corte['evaluaciones'])
                    ->map(fn($eval) => [
                        'evaluacion_id' => $eval['evaluacion_id'],
                        'tecnica_id' => $eval['tecnica_id'],
                        'ponderacion' => (int) $eval['ponderacion'],
                        'fecha_evaluacion' => $eval['fecha_evaluacion'],
                        'forma_participacion' => $eval['forma_participacion'],
                        'integrantes' => $eval['integrantes'] ?? null,
                    ])
                    ->toArray();

                return [
                    'corte' => $corte['corte'],
                    'estatus' => $corte['estatus'],
                    'ultimo_motivo_rechazo' => $corte['ultimo_motivo_rechazo'],
                    'recursos' => $recursos,
                    'estrategias' => $estrategias,
                    'contenidos' => $contenidos,
                    'evaluaciones' => $evaluaciones,
                    'indicadores_logro' => $corte['indicadores_logros'] ?? '',
                ];
            })
            ->toArray();

        // Inicializa los cortes si no hay ninguno para que el formulario se muestre correctamente
        if (empty($this->form->cortes)) {
            $this->addCorte();
        } else {
            // Asegúrate de que todos los arrays anidados estén inicializados para evitar errores de null
            foreach ($this->form->cortes as $corteIndex => $corte) {
                $this->form->cortes[$corteIndex]['recursos'] = $corte['recursos'] ?? [];
                $this->form->cortes[$corteIndex]['estrategias'] = $corte['estrategias'] ?? [];
                $this->form->cortes[$corteIndex]['contenidos'] = $corte['contenidos'] ?? [];
                $this->form->cortes[$corteIndex]['evaluaciones'] = $corte['evaluaciones'] ?? [];
            }
        }
    }

    // Carga las opciones para los selects usando el repositorio
    private function loadDropdownOptions()
    {
        $this->recursosDisponibles = $this->planificacionCreateRepo->select_recursos();
        $this->estrategiasDisponibles = $this->planificacionCreateRepo->select_tecnica_actividad();
        $this->evaluacionesDisponibles = $this->planificacionCreateRepo->select_evaluaciones();
        $this->tecnicaDisponibles = $this->planificacionCreateRepo->select_tecnica();
        $this->bibliografiasDisponibles = $this->planificacionCreateRepo->select_bibliografias();
    }

    // Métodos específicos para añadir/eliminar cortes
    public function addCorte()
    {
        $this->form->cortes[] = [
            'corte' => count($this->form->cortes) + 1,
            'estatus' => 2, // Estatus inicial para un nuevo corte (Pendiente)
            'recursos' => [],
            'estrategias' => [],
            'contenidos' => [],
            'evaluaciones' => [],
            'ultimo_motivo_rechazo' => null,
            'indicadores_logro' => '',
        ];
        // Al añadir un nuevo corte, asegúrate de añadir al menos un contenido y una evaluación
        $lastCorteIndex = count($this->form->cortes) - 1;
        $this->addItem($lastCorteIndex, 'contenidos');
        $this->addItem($lastCorteIndex, 'evaluaciones');
    }

    public function removeCorte($index)
    {
        unset($this->form->cortes[$index]);
        $this->form->cortes = array_values($this->form->cortes);
        // Reajustar los números de corte
        foreach ($this->form->cortes as $idx => $corte) {
            $this->form->cortes[$idx]['corte'] = $idx + 1;
        }
    }

    // Métodos genéricos para manejo de arrays dinámicos
    public function addItem($corteIndex, $arrayName, $contenidoIndex = null)
    {
        // Define templates por defecto sin el id
        $defaultTemplates = [
            'contenidos' => ['contenido_id' => ''],
            'recursos' => ['recurso_id' => ''],
            'estrategias' => ['tema_id' => '', 'actividad' => ''],
            'evaluaciones' => [
                'fecha_evaluacion' => '',
                'evaluacion_id' => '',
                'ponderacion' => 5,
                'tecnica_id' => '',
                'forma_participacion' => '',
                'integrantes' => null
            ],
            'bibliografias' => ['bibliografia_id' => ''],
        ];

        $template = $defaultTemplates[$arrayName] ?? [];

        if ($arrayName === 'bibliografias') {
            $this->form->bibliografias[] = $template;
        } else { // Para recursos, estrategias, evaluaciones, contenidos (dentro de un corte)
            if (isset($this->form->cortes[$corteIndex])) { // Asegurarse de que el corte exista
                $this->form->cortes[$corteIndex][$arrayName][] = $template;
            }
        }
    }

    public function removeItem($corteIndex, $arrayName, $itemIndex)
    {
        if ($arrayName === 'bibliografias') {
            if (isset($this->form->bibliografias[$itemIndex])) {
                unset($this->form->bibliografias[$itemIndex]);
                $this->form->bibliografias = array_values($this->form->bibliografias);
            }
        } else { // Para contenidos, recursos, estrategias, evaluaciones (dentro de un corte)
            if (isset($this->form->cortes[$corteIndex][$arrayName][$itemIndex])) {
                unset($this->form->cortes[$corteIndex][$arrayName][$itemIndex]);
                $this->form->cortes[$corteIndex][$arrayName] = array_values($this->form->cortes[$corteIndex][$arrayName]);
            }
        }
    }


    protected function loadContenidosUnidad()
    {
        $this->contenidosDisponibles = $this->planificacionCreateRepo->select_contenidos($this->id_unidad_curricular);
    }

    public function toggleDetallesUnidad()
    {
        $this->mostrarDetallesUnidad = !$this->mostrarDetallesUnidad;
    }

    public function updated($propertyName)
    {
        $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
        $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
        $this->form->id_lapso_academico = $this->id_lapso_academico;

        $field = str_replace('form.', '', $propertyName);

        if (str_contains($field, 'forma_participacion')) {
            $this->form->validate();
        } else {
            $this->form->validateOnly($field);
        }
    }

    public function getTotalPonderacionForCorte($corteIndex)
    {
        return $this->form->getTotalPonderacionForCorte($corteIndex);
    }

    // Método para guardar los cambios
    public function savePlanificacion()
    {
        $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
        $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
        $this->form->id_lapso_academico = $this->id_lapso_academico;

        $this->form->validate();

        $success = $this->planificacionEditRepo->updatePlanificacion($this->planificacionId, [
            'bibliografias' => $this->form->bibliografias,
            'cortes' => $this->form->cortes
        ]);

        if ($success) {
            session()->flash('message', 'Planificación actualizada exitosamente.');
            return redirect()->to('/planificacion/list');
        } else {
            session()->flash('error', 'Error al actualizar la planificación.');
        }
    }

    public function render()
    {
        return view('livewire.pages.planificacion.update-planificacion');
    }
}

<?php

namespace App\Livewire\Planificacion;

use App\Repositories\Planificacion\PlanificacionCreateRepo;
use Illuminate\Support\{Collection, Facades\Auth, Facades\Gate, Str};
use Livewire\Component;
use Carbon\Carbon;

class CreatePlanificacion extends Component
{
    public $docente_id, $docenteNombre, $docenteRol, $proposito, $mallaNombre, $lapsoNombre;
    public $isCoordinador = false;
    public $openUnidad = 0;
    public $maxUnidadAlcanzada = 0;
    public $planificacionDraftId = null;
    public Collection $tecnica, $recursosMaestros, $evaluaciones, $bibliografiasMaestras, $asignaciones, $tecnicasActividad;
    public \App\Livewire\Forms\Planificacion\CreatePlanificacionForm $form;
    // Removed public array $temasPorUnidad, $todosLosContenidos, $todosLosObjetivos to avoid dehydration issues

    public $formasParticipacion = [];

    // Modal properties
    public $showObjetivoModal = false;
    public $newObjetivoNombre = '';
    public $selectedTemaIdForObjetivo = null;

    public $showBiblioModal = false;
    public $newBiblioNombre = '';

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
        $this->contenidosPorTema = collect();

        $this->loadInitialData();
        $this->verifyDocenteRole();
        $this->isCoordinador = $this->planificacionRepository->isCoordinador($this->docente_id);
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
                    // Note: temas, contenidos and objetivos are now loaded dynamically in render()
                    // to prevent Livewire dehydration array casting issues.

                    // Obtener propósito de la unidad curricular
                    $unidad = $this->planificacionRepository->getUnidadCurricular($detalle->id_unidad_curricular);
                    if ($unidad) {
                        $this->proposito = property_exists($unidad, 'proposito_unidad_curricular') ? $unidad->proposito_unidad_curricular : '';
                    }

                    // Obtener Malla y Lapso
                    $malla = $this->planificacionRepository->getMallaByAsignacion($value);
                    $this->mallaNombre = $malla ? $malla->mal_nombre : 'No especificada';

                    $lapso = $this->planificacionRepository->getLapsoAcademicoByAsignacion($value);
                    $this->lapsoNombre = $lapso ? $lapso->lap_nombre : 'No especificado';

                    // Obtener nombre y rol del docente
                    $this->docenteNombre = "{$asignacion->name} {$asignacion->apellido}";
                    $this->docenteRol = 'Docente';
                }
            }
        } else {
            $this->temasPorUnidad = [];
            $this->todosLosContenidos = collect();
            $this->todosLosObjetivos = collect();
            $this->proposito = '';
            $this->mallaNombre = '';
            $this->lapsoNombre = '';
            $this->verifyDocenteRole(); // Reset to current user if no assignment selected
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
        $this->tecnicasActividad = $this->planificacionRepository->select_tecnica_actividad();

        // Cargar asignaciones: Si es coordinador ve todas, si es docente solo las suyas
        if ($this->isCoordinador) {
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
            $user = Auth::user();
            $this->docenteNombre = $user->name;
            $this->docenteRol = $user->rol->rol_nombre ?? 'Docente';
        } else {
            session()->flash('error', 'Acceso denegado.');
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

        // Si cambia el profesor asignado (la asignatura), debemos resetear el ID de borrador
        // y buscar si ya existe una planificación para esta nueva asignatura.
        if ($field === 'id_profesor_asignado') {
            $this->planificacionDraftId = null;
            if ($this->form->id_profesor_asignado) {
                $existing = \App\Models\Planificacion::where('id_profesor_asignado', $this->form->id_profesor_asignado)
                    ->whereIn('estatus', ['1', '2', '3', '4'])
                    ->latest('id_planificacion')
                    ->first();
                if ($existing) {
                    $this->planificacionDraftId = $existing->id_planificacion;
                }
            }
        }
        if (str_contains($field, 'unidades') || str_contains($field, 'id_profesor_asignado') || str_contains($field, 'tipos_seccion') || str_contains($field, 'proposito_unidad')) {
            $this->autoSaveSection();
        }

        if (str_contains($field, 'ponderacion')) {
            if (preg_match('/unidades\.(\d+)\.evaluaciones\.(\d+)\.ponderacion/', $field, $matches)) {
                $unidadIndex = $matches[1];
                $evalIndex = $matches[2];
                $val = floatval($this->form->unidades[$unidadIndex]['evaluaciones'][$evalIndex]['ponderacion'] ?? 0);
                
                $totalOthers = 0;
                foreach ($this->form->unidades[$unidadIndex]['evaluaciones'] as $i => $ev) {
                    if ($i != $evalIndex) {
                        $totalOthers += floatval($ev['ponderacion'] ?? 0);
                    }
                }
                
                $maxAllowed = max(0, 25 - $totalOthers);
                if ($val > $maxAllowed) {
                    $this->form->unidades[$unidadIndex]['evaluaciones'][$evalIndex]['ponderacion'] = $maxAllowed;
                }
                
                try {
                    $this->validateOnly("form.unidades.$unidadIndex.total_ponderacion_check");
                } catch (\Illuminate\Validation\ValidationException $e) {}
            }
            $this->form->validateOnly($field);
        } elseif (str_contains($field, 'forma_participacion')) {
            if (preg_match('/unidades\.(\d+)\./', $field, $matches)) {
                $index = $matches[1];
                try {
                    $this->validateOnly("form.unidades.$index.total_ponderacion_check");
                } catch (\Illuminate\Validation\ValidationException $e) {}
            }
            $this->form->validateOnly($field);
        } else {
            $this->form->validateOnly($field);
        }
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
            'estrategias' => [['tecnica_actividad_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]]],
            'evaluaciones' => [['fecha_evaluacion' => '', 'evaluacion_id' => '', 'ponderacion' => 5, 'tecnica_id' => '', 'forma_participacion' => '', 'integrantes' => null]],
            'bibliografias' => [['bibliografia_id' => '']],
            'indicadores_logro' => '',
            'total_ponderacion_check' => 0
        ];
    }

    public function addItem($unidadIndex, $arrayName, $parentIndex = null)
    {
        if ($arrayName === 'bibliografias') {
            if (!$this->form->areBibliografiasFilled($unidadIndex)) {
                $this->showAlert('error', 'Debe seleccionar la bibliografía actual antes de añadir otra.');
                return;
            }
            // Add bibliography to unit
            $this->form->unidades[$unidadIndex]['bibliografias'][] = ['bibliografia_id' => ''];
        } elseif ($arrayName === 'objetivos') {
            if (!$this->form->areObjetivosFilled($unidadIndex)) {
                $this->showAlert('error', 'Debe rellenar completamente el tema/objetivo actual antes de añadir otro.');
                return;
            }
            // Add new objective block
            $this->form->unidades[$unidadIndex]['objetivos'][] = [
                'tema_id' => '',
                'objetivo_id' => '',
                'contenidos' => [['contenido_id' => '']]
            ];
        } elseif ($arrayName === 'contenidos') {
            // Check if objective is selected
            $objetivoId = $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['objetivo_id'] ?? null;
            if (empty($objetivoId)) {
                session()->flash('error', 'Debe seleccionar un objetivo primero.');
                return;
            }
            if (!$this->form->areContenidosFilled($unidadIndex, $parentIndex)) {
                $this->showAlert('error', 'Debe seleccionar el contenido actual antes de añadir otro.');
                return;
            }
            // Add content to specific objective
            $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][] = ['contenido_id' => ''];

        } elseif ($arrayName === 'estrategia_recursos') {
            if (!$this->form->areRecursosFilled($unidadIndex, $parentIndex)) {
                $this->showAlert('error', 'Debe seleccionar el recurso actual antes de añadir otro.');
                return;
            }
            // Add resource to specific strategy
            // $parentIndex is the strategy index
            $this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][] = ['recurso_id' => ''];

        } else {
            // Fallback for evaluaciones and estrategias
            if ($arrayName === 'evaluaciones') {
                if (!$this->form->areEvaluacionesFilled($unidadIndex)) {
                    $this->showAlert('error', 'Debe rellenar completamente la evaluación actual antes de añadir otra.');
                    return;
                }
            } elseif ($arrayName === 'estrategias') {
                if (!$this->form->areEstrategiasFilled($unidadIndex)) {
                    $this->showAlert('error', 'Debe rellenar completamente la estrategia actual antes de añadir otra.');
                    return;
                }
            }
            $defaultTemplates = [
                'evaluaciones' => [
                    'fecha_evaluacion' => '',
                    'evaluacion_id' => '',
                    'ponderacion' => 5,
                    'tecnica_id' => '',
                    'forma_participacion' => '',
                    'integrantes' => null
                ],
                'estrategias' => ['tecnica_actividad_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]],
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

    protected function loadContenidosUnidad()
    {
        $temasPorUnidadLocal = [];
        $todosLosContenidosLocal = collect();
        $todosLosObjetivosLocal = collect();

        if ($this->form->id_profesor_asignado) {
            $detalle = $this->planificacionRepository->getDetalleProfesorAsignado($this->form->id_profesor_asignado);
            if ($detalle) {
                $todosLosTemas = $this->planificacionRepository->select_temas_por_unidad($detalle->id_unidad_curricular);
                foreach (range(1, 4) as $num) {
                    $temasPorUnidadLocal[$num] = $todosLosTemas->where('unidad_tema', (string) $num)->values();
                }
                $todosLosContenidosLocal = $this->planificacionRepository->select_contenidos($detalle->id_unidad_curricular);
                $todosLosObjetivosLocal = $this->planificacionRepository->select_objetivos($detalle->id_unidad_curricular);
            }
        }

        return [
            'temasPorUnidad' => $temasPorUnidadLocal,
            'todosLosContenidos' => $todosLosContenidosLocal,
            'todosLosObjetivos' => $todosLosObjetivosLocal
        ];
    }

    public function render()
    {
        $contenidosData = $this->loadContenidosUnidad();

        return view('livewire.pages.planificacion.create-planificacion', [
            'weekDays' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'], // No longer used but kept for compatibility
            'timeSlots' => $this->generateTimeSlots(),
            'temasPorUnidad' => $contenidosData['temasPorUnidad'],
            'todosLosContenidos' => $contenidosData['todosLosContenidos'],
            'todosLosObjetivos' => $contenidosData['todosLosObjetivos']
        ]);
    }

    protected function generateTimeSlots()
    {
        return collect(range(8, 18))->map(fn($h) => sprintf('%02d:00', $h));
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function autoSaveSection()
    {
        // Solo requerimos el profesor asignado para crear el registro inicial
        if (!$this->form->id_profesor_asignado) {
            return;
        }

        try {
            if ($this->planificacionDraftId) {
                // Ya existe un borrador, actualizamos
                $repo = new \App\Repositories\Planificacion\PlanificacionEditRepo();
                $repo->updatePlanificacion($this->planificacionDraftId, [
                    'unidades' => $this->form->unidades,
                    'proposito_unidad' => $this->form->proposito_unidad
                ]);
            } else {
                // Primera vez, creamos borrador
                $id = $this->planificacionRepository->savePlanificacionTransaccion(
                    $this->form->id_profesor_asignado,
                    $this->form->unidades,
                    $this->form->tipos_seccion ?? [],
                    '4', // Estatus Borrador (Incompleta)
                    $this->form->proposito_unidad
                );
                if ($id) {
                    $this->planificacionDraftId = $id;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Auto-save failed: " . $e->getMessage());
        }
    }

    public function savePlanificacion()
    {
        try {
            $this->form->validate();

            $user = \Illuminate\Support\Facades\Auth::user();
            $firma = \Illuminate\Support\Facades\DB::table('firma')
                ->where('id_usuario', $user->usu_codigo)
                ->where('estatus', '1')
                ->first();

            if (!$firma) {
                $this->autoSaveSection();
                $this->showAlert('error', 'No puedes enviar la planificación porque no has subido tu firma al sistema. Por favor, regístrala antes de enviarla. Tu progreso actual se ha guardado como borrador.');
                return;
            }

            if ($this->planificacionDraftId) {
                // Actualizar borrador existente a estatus final
                $repo = new \App\Repositories\Planificacion\PlanificacionEditRepo();
                $repo->updatePlanificacion($this->planificacionDraftId, [
                    'unidades' => $this->form->unidades,
                    'proposito_unidad' => $this->form->proposito_unidad
                ]);
                // Cambiar estatus a '2' (enviada para aprobación) y registrar firma
                $draft = \App\Models\Planificacion::find($this->planificacionDraftId);
                if ($draft) {
                    $draft->update(['estatus' => '2', 'id_firma_docente' => $firma->id_firma]);
                }
            } else {
                $this->planificacionRepository->savePlanificacionTransaccion(
                    $this->form->id_profesor_asignado,
                    $this->form->unidades,
                    $this->form->tipos_seccion,
                    '2',
                    $this->form->proposito_unidad,
                    $firma->id_firma
                );
            }

            $this->showAlert('success', '¡Guardado!, en espera que lo aprueben (puede verlo en la campana de notificaciones)', '/planificacion/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "No se puede guardar. Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (\Exception $e) {
            $this->showAlert('error', 'Error al guardar la planificación: ' . $e->getMessage());
        }
    }

    public function validateSectionAndAdvance($currentSection, $nextSection, $unidadIndex)
    {
        $isValid = false;
        $errorMsg = '';
        
        switch ($currentSection) {
            case 'tematica':
                $isValid = $this->form->isTematicaComplete($unidadIndex);
                $errorMsg = 'Debe completar toda la temática general antes de avanzar.';
                break;
            case 'estrategias':
                $isValid = $this->form->isEstrategiasComplete($unidadIndex);
                $errorMsg = 'Debe completar las estrategias pedagógicas antes de avanzar.';
                break;
            case 'indicadores':
                $isValid = $this->form->isIndicadoresComplete($unidadIndex);
                $errorMsg = 'Debe completar los indicadores de logro antes de avanzar.';
                break;
            case 'evaluacion':
                $isValid = $this->form->isEvaluacionComplete($unidadIndex);
                $errorMsg = 'Debe completar el plan de evaluación (incluyendo ponderaciones) antes de avanzar.';
                break;
            case 'bibliografias':
                $isValid = $this->form->isBibliografiasComplete($unidadIndex);
                $errorMsg = 'Debe completar las referencias bibliográficas antes de avanzar.';
                break;
            default:
                $isValid = true;
        }

        if (!$isValid) {
            $this->showAlert('error', $errorMsg);
            return;
        }

        $this->autoSaveSection();
        $data = json_encode(['next' => $nextSection, 'index' => $unidadIndex]);
        $this->js("window.dispatchEvent(new CustomEvent('advance-section', { detail: {$data} }))");
    }

    public function irAUnidad($targetIndex)
    {
        // Guardar progreso automáticamente antes de cambiar de unidad
        $this->autoSaveSection();

        // Si intentamos avanzar a una unidad futura
        if ($targetIndex > $this->openUnidad) {
            // Contar cuántas unidades están completas actualmente
            $unidadesCompletas = 0;
            foreach (range(0, 3) as $i) {
                if ($this->form->isUnidadComplete($i)) {
                    $unidadesCompletas++;
                }
            }

            // REGLA: Si ya tiene más de un corte completo (2 o más), permitimos movimiento más libre
            // Pero si intenta ir más allá de lo que ha completado secuencialmente, validamos.
            if ($unidadesCompletas <= 1) {
                // Si tiene 1 o menos cortes completos, la validación es estricta:
                // Debe completar la unidad actual para poder ver la siguiente.
                $validator = $this->getUnidadValidator($this->openUnidad);
                
                if ($validator->fails()) {
                    $errors = $validator->errors()->all();
                    $msg = "No puedes avanzar a la Unidad " . ($targetIndex + 1) . " aún. Debes completar la Unidad " . ($this->openUnidad + 1) . ":\n\n• " . implode("\n• ", $errors);
                    $this->showAlert('error', $msg);
                    return;
                }
            } else {
                // Si tiene más de un corte completo, permitimos moverte "tranquilamente" entre lo ya alcanzado
                // Pero si intenta ir a una unidad que nunca ha sido validada, forzamos validación de la actual.
                if ($targetIndex > $this->maxUnidadAlcanzada) {
                    $validator = $this->getUnidadValidator($this->openUnidad);
                    if ($validator->fails()) {
                        $errors = $validator->errors()->all();
                        $msg = "Para avanzar a nuevas unidades, completa primero la actual:\n\n• " . implode("\n• ", $errors);
                        $this->showAlert('error', $msg);
                        return;
                    }
                }
            }
        }

        // Si la validación pasó o si vamos hacia atrás, permitimos el cambio
        if ($targetIndex > $this->maxUnidadAlcanzada) {
            $this->maxUnidadAlcanzada = $targetIndex;
        }

        $this->openUnidad = $targetIndex;
        $this->dispatch('scroll-to-top');
    }

    protected function getUnidadValidator($index)
    {
        // Pedimos solo las reglas de esta unidad específica
        $allRules = $this->form->rules($index);
        $rules = [];
        $messages = [];
        $attributes = [];

        // Mapear mensajes y atributos con prefijo 'form.'
        foreach ($this->form->messages() as $key => $msg) {
            $messages["form.$key"] = $msg;
        }
        foreach ($this->form->validationAttributes() as $key => $attr) {
            $attributes["form.$key"] = $attr;
        }

        // Reglas globales
        foreach (['id_profesor_asignado', 'tipos_seccion'] as $globalField) {
            if (isset($allRules[$globalField])) $rules["form.$globalField"] = $allRules[$globalField];
        }

        // Reglas de la unidad
        $unitPrefix = "unidades.$index.";
        foreach ($allRules as $key => $rule) {
            if (str_starts_with($key, $unitPrefix)) {
                $rules["form.$key"] = $rule;
            }
        }
        $rules["form.unidades.$index.total_ponderacion_check"] = $allRules["unidades.$index.total_ponderacion_check"] ?? [];

        return \Illuminate\Support\Facades\Validator::make(
            ['form' => $this->form->all()], 
            $rules, 
            $messages, 
            $attributes
        );
    }

    public function validarYAvanzar($index)
    {
        $this->irAUnidad($index + 1);
    }

    public function unidadAnterior($index)
    {
        $this->irAUnidad($index - 1);
    }

    public function saveProgress($index)
    {
        if (!$this->form->id_profesor_asignado || empty($this->form->tipos_seccion)) {
            $this->showAlert('error', 'Debe seleccionar una asignatura y al menos un tipo de sección antes de guardar el progreso.');
            return;
        }

        try {
            $this->planificacionRepository->savePlanificacionTransaccion(
                $this->form->id_profesor_asignado,
                $this->form->unidades,
                $this->form->tipos_seccion,
                '4',
                $this->form->proposito_unidad
            );

            $this->showAlert('success', 'Progreso de la unidad ' . ($index + 1) . ' guardado exitosamente como borrador.', '/planificacion/list');
        } catch (\Exception $e) {
            $this->showAlert('error', 'Error al guardar el progreso: ' . $e->getMessage());
        }
    }

    public function openObjetivoModal($temaId)
    {
        if (!$this->isCoordinador) {
            session()->flash('error', 'Solo los coordinadores pueden añadir nuevos objetivos.');
            return;
        }

        if (!$temaId || $temaId === '') {
            session()->flash('error', 'Debe seleccionar un tema primero.');
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
        if (!$this->isCoordinador) {
            session()->flash('error', 'Solo los coordinadores pueden añadir nuevos objetivos.');
            $this->closeObjetivoModal();
            return;
        }

        $this->validate([
            'newObjetivoNombre' => 'required|min:3|max:255',
            'selectedTemaIdForObjetivo' => 'required|exists:tema_unidad,id_tema_unidad',
        ]);

        try {
            $this->planificacionRepository->saveNuevoObjetivo($this->newObjetivoNombre, $this->selectedTemaIdForObjetivo);

            // Objetivos list is now automatically reloaded in render(), 
            // no need to manually reload it here anymore.

            session()->flash('message', 'Objetivo creado correctamente.');
            $this->closeObjetivoModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el objetivo: ' . $e->getMessage());
        }
    }

    public function openBiblioModal()
    {
        $this->newBiblioNombre = '';
        $this->showBiblioModal = true;
    }

    public function closeBiblioModal()
    {
        $this->showBiblioModal = false;
        $this->reset(['newBiblioNombre']);
    }

    public function saveBiblio()
    {
        $this->validate([
            'newBiblioNombre' => 'required|min:3|max:1000',
        ], [
            'newBiblioNombre.required' => 'El nombre de la bibliografía es obligatorio.',
            'newBiblioNombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'newBiblioNombre.max' => 'El nombre es demasiado largo.',
        ]);

        try {
            $this->planificacionRepository->saveNuevaBibliografia($this->newBiblioNombre);

            // Recargar bibliografías
            $this->bibliografiasMaestras = $this->planificacionRepository->select_bibliografias();

            session()->flash('message', 'Bibliografía creada correctamente.');
            $this->closeBiblioModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la bibliografía: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Livewire\Planificacion;

use App\Repositories\Planificacion\PlanificacionCreateRepo;
use App\Repositories\Planificacion\PlanificacionEditRepo;
use App\Repositories\Planificacion\PlanificacionViewRepo;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Livewire\Forms\Planificacion\UpdatePlanificacionForm;

class UpdatePlanificacion extends Component
{
    protected $planificacionCreateRepo;
    protected $planificacionEditRepo;
    protected $planificacionViewRepo;

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

    public UpdatePlanificacionForm $form;
    public $openUnidad = 0;
    public $maxUnidadAlcanzada = 0; // Se actualizará según el progreso real

    public Collection $recursosMaestros;
    public Collection $estrategiasDisponibles;
    public Collection $evaluaciones;
    public Collection $tecnica;
    public Collection $bibliografiasMaestras;
    public Collection $tecnicasActividad;
    // Removed public collections for temas, contenidos, objetivos to avoid dehydration issues

    public $id_unidad_curricular;
    public $lapso_fecha_inicio;
    public $lapso_fecha_fin;

    public $mostrarDetallesUnidad = false;
    public $locked = false;
    public $isCoordinador = false;

    // Modal properties
    public $showObjetivoModal = false;
    public $newObjetivoNombre = '';
    public $selectedTemaIdForObjetivo = null;

    public $showBiblioModal = false;
    public $newBiblioNombre = '';

    public function __construct()
    {
        $this->planificacionCreateRepo = new PlanificacionCreateRepo();
        $this->planificacionEditRepo = new PlanificacionEditRepo();
        $this->planificacionViewRepo = new PlanificacionViewRepo();
    }

    public function mount($planificacionId)
    {
        $this->recursosMaestros = collect();
        $this->estrategiasDisponibles = collect();
        $this->evaluaciones = collect();
        $this->tecnica = collect();
        $this->bibliografiasMaestras = collect();
        $this->tecnicasActividad = collect();
        $this->todosLosContenidos = collect();
        $this->todosLosObjetivos = collect();

        $this->planificacionId = $planificacionId;
        $planificacion = $this->planificacionViewRepo->getDetallesPlanificacion($planificacionId);

        if (!$planificacion) {
            session()->flash('error', 'Planificación no encontrada.');
            return redirect()->to('/planificacion/list');
        }

        $this->docente_id = $planificacion['docente_id'];

        if (Auth::id() !== $this->docente_id && Gate::denies('editar-planificacion')) {
            abort(403, 'No tienes permiso para editar esta planificación.');
        }

        $this->isCoordinador = $this->planificacionCreateRepo->isCoordinador(Auth::id());

        $this->loadDropdownOptions();

        $this->docente_nombre = $planificacion['docente_nombre'];
        $this->docente_apellido = $planificacion['docente_apellido'];
        $this->cedula = $planificacion['cedula'];
        $this->id_unidad_curricular = $planificacion['id_unidad_curricular'];
        $this->lapso_fecha_inicio = $planificacion['lapso_fecha_inicio'];
        $this->lapso_fecha_fin = $planificacion['lapso_fecha_fin'];
        $this->id_lapso_academico = $planificacion['id_lapso_academico'];
        $this->nombre_unidad_curricular = $planificacion['nombre_unidad_curricular'];
        $this->nombre_seccion = $planificacion['nombre_seccion'];
        
        $this->locked = $planificacion['estatus'] == 1;
        $this->nombre_lapso = $planificacion['nombre_lapso'];
        $this->docente_rol = $planificacion['docente_rol'] ?? 'Docente';
        $this->form->proposito_unidad = $planificacion['proposito_unidad'] ?? '';

        $malla = $this->planificacionCreateRepo->getMallaByAsignacion($planificacion['id_detalle_profesor_asignado'] ?? null);
        $this->nombre_malla = $malla ? $malla->mal_nombre : 'No especificada';

        // Optional: Load anything needed for initial setup
        $contenidosData = $this->loadContenidosUnidad();

        $unidades = [];
        $firstPendiente = 0;
        foreach ($planificacion['unidades'] as $index => $corte) {
            if ($corte['estatus'] == 2 && $firstPendiente === 0 && $index > 0) {
                // Keep 0 as default if the first one is pending
                $firstPendiente = $index;
            } elseif ($corte['estatus'] == 2 && $index == 0) {
                $firstPendiente = 0;
            }

            $objetivosDict = [];
            foreach ($corte['contenidos'] as $cont) {
                $objId = isset($cont['id_objetivo']) ? (string) $cont['id_objetivo'] : null;
                $temaId = isset($cont['tema_id']) ? (string) $cont['tema_id'] : null;
                $contId = isset($cont['contenido_id']) ? (string) $cont['contenido_id'] : null;

                // Solo procesar si tenemos datos mínimos válidos
                if (!$objId || !$temaId) continue;

                if (!isset($objetivosDict[$objId])) {
                    $objetivosDict[$objId] = [
                        'tema_id' => $temaId,
                        'objetivo_id' => $objId,
                        'contenidos' => []
                    ];
                }
                
                // Evitar duplicados de contenido en el mismo objetivo
                $exists = false;
                foreach ($objetivosDict[$objId]['contenidos'] as $existingCont) {
                    if ($existingCont['contenido_id'] == $contId) {
                        $exists = true;
                        break;
                    }
                }
                
                if (!$exists && $contId) {
                    $objetivosDict[$objId]['contenidos'][] = ['contenido_id' => $contId];
                }
            }
            
            $objetivos = array_values($objetivosDict);
            if (empty($objetivos)) {
                $objetivos = [['tema_id' => '', 'objetivo_id' => '', 'contenidos' => [['contenido_id' => '']]]];
            }

            $estrategiasForm = [];
            if (!empty($corte['estrategias'])) {
                $est = $corte['estrategias'][0]; 
                $recursosNames = collect($corte['recursos'])->map(fn($r) => ['recurso_id' => $r['recurso']])->toArray();
                $estrategiasForm[] = [
                    'tecnica_actividad_id' => $est['titulo_tema'] ?? '', 
                    'actividad' => $est['actividad'] ?? '',
                    'recursos' => empty($recursosNames) ? [['recurso_id' => '']] : $recursosNames
                ];
            } else {
                $estrategiasForm = [['tecnica_actividad_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]]];
            }

            $evaluaciones = collect($corte['evaluaciones'])
                ->map(fn($eval) => [
                    'evaluacion_id' => (string) $eval['evaluacion'],
                    'tecnica_id' => (string) $eval['tecnica'],
                    'ponderacion' => (int) $eval['ponderacion'],
                    'fecha_evaluacion' => (string) $eval['fecha_evaluacion'],
                    'forma_participacion' => (string) $eval['forma_participacion'],
                    'integrantes' => $eval['integrantes'] ?? null,
                ])
                ->toArray();
            if (empty($evaluaciones)) {
                $evaluaciones = [['fecha_evaluacion' => '', 'evaluacion_id' => '', 'ponderacion' => 5, 'tecnica_id' => '', 'forma_participacion' => '', 'integrantes' => null]];
            }

            $bibliografias = collect($corte['bibliografias'] ?? [])->map(fn($b) => ['bibliografia_id' => $b['bibliografia']])->toArray();
            if (empty($bibliografias)) {
                $bibliografias = [['bibliografia_id' => '']];
            }

            $unidades[] = [
                'numero' => $corte['numero'],
                'estatus' => $corte['estatus'],
                'ultimo_motivo_rechazo' => $corte['ultimo_motivo_rechazo'],
                'objetivos' => $objetivos,
                'estrategias' => $estrategiasForm,
                'evaluaciones' => $evaluaciones,
                'bibliografias' => $bibliografias,
                'indicadores_logro' => $corte['indicadores_logro'] ?? '',
            ];
        }

        while (count($unidades) < 4) {
            $idx = count($unidades);
            $unidades[] = [
                'numero' => $idx + 1,
                'estatus' => 2,
                'objetivos' => [['tema_id' => '', 'objetivo_id' => '', 'contenidos' => [['contenido_id' => '']]]],
                'estrategias' => [['tecnica_actividad_id' => '', 'actividad' => '', 'recursos' => [['recurso_id' => '']]]],
                'evaluaciones' => [['fecha_evaluacion' => '', 'evaluacion_id' => '', 'ponderacion' => 5, 'tecnica_id' => '', 'forma_participacion' => '', 'integrantes' => null]],
                'bibliografias' => [['bibliografia_id' => '']],
                'indicadores_logro' => ''
            ];
        }

        $this->form->unidades = $unidades;
        $this->openUnidad = $firstPendiente;

        // Calcular progreso inicial para maxUnidadAlcanzada
        $this->maxUnidadAlcanzada = 0;
        foreach (range(0, 3) as $i) {
            if ($this->form->isUnidadComplete($i)) {
                $this->maxUnidadAlcanzada = $i + 1;
            } else {
                break;
            }
        }
        // Asegurarnos que maxUnidadAlcanzada no exceda 3 (índice máximo)
        $this->maxUnidadAlcanzada = min($this->maxUnidadAlcanzada, 3);
    }

    private function loadDropdownOptions()
    {
        $this->recursosMaestros = $this->planificacionCreateRepo->select_recursos();
        $this->tecnicasActividad = $this->planificacionCreateRepo->select_tecnica_actividad();
        $this->evaluaciones = $this->planificacionCreateRepo->select_evaluaciones();
        $this->tecnica = $this->planificacionCreateRepo->select_tecnica();
        $this->bibliografiasMaestras = $this->planificacionCreateRepo->select_bibliografias();
    }

    protected function loadContenidosUnidad()
    {
        $todosLosTemas = $this->planificacionCreateRepo->select_temas_por_unidad($this->id_unidad_curricular);
        $temasPorUnidadLocal = [];
        foreach (range(1, 4) as $num) {
            $temasPorUnidadLocal[$num] = $todosLosTemas->where('unidad_tema', (string) $num)->values();
        }
        return [
            'temasPorUnidad' => $temasPorUnidadLocal,
            'todosLosContenidos' => $this->planificacionCreateRepo->select_contenidos($this->id_unidad_curricular),
            'todosLosObjetivos' => $this->planificacionCreateRepo->select_objetivos($this->id_unidad_curricular)
        ];
    }

    public function addItem($unidadIndex, $arrayName, $parentIndex = null)
    {
        if ($arrayName === 'bibliografias') {
            $this->form->unidades[$unidadIndex]['bibliografias'][] = ['bibliografia_id' => ''];
        } elseif ($arrayName === 'objetivos') {
            $this->form->unidades[$unidadIndex]['objetivos'][] = [
                'tema_id' => '',
                'objetivo_id' => '',
                'contenidos' => [['contenido_id' => '']]
            ];
        } elseif ($arrayName === 'contenidos') {
            $objetivoId = $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['objetivo_id'] ?? null;
            if (empty($objetivoId)) {
                session()->flash('error', 'Debe seleccionar un objetivo primero.');
                return;
            }
            $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][] = ['contenido_id' => ''];
        } elseif ($arrayName === 'estrategia_recursos') {
            $this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][] = ['recurso_id' => ''];
        } else {
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
            if (isset($this->form->unidades[$unidadIndex]['objetivos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['objetivos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['objetivos'] = array_values($this->form->unidades[$unidadIndex]['objetivos']);
            }
        } elseif ($arrayName === 'contenidos') {
            if (isset($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos'] = array_values($this->form->unidades[$unidadIndex]['objetivos'][$parentIndex]['contenidos']);
            }
        } elseif ($arrayName === 'estrategia_recursos') {
            if (isset($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'][$itemIndex]);
                $this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos'] = array_values($this->form->unidades[$unidadIndex]['estrategias'][$parentIndex]['recursos']);
            }
        } else {
            if (isset($this->form->unidades[$unidadIndex][$arrayName][$itemIndex])) {
                unset($this->form->unidades[$unidadIndex][$arrayName][$itemIndex]);
                $this->form->unidades[$unidadIndex][$arrayName] = array_values($this->form->unidades[$unidadIndex][$arrayName]);
            }
        }
    }

    public function unidadAnterior($index)
    {
        if ($index > 0) {
            $this->openUnidad = $index - 1;
            $this->dispatch('scroll-to-top');
        }
    }

    public function updated($propertyName)
    {
        $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
        $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
        $this->form->id_lapso_academico = $this->id_lapso_academico;

        $field = str_replace('form.', '', $propertyName);

        if (str_contains($field, 'unidades') || str_contains($field, 'proposito_unidad')) {
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

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function autoSaveSection()
    {
        try {
            $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
            $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
            $this->form->id_lapso_academico = $this->id_lapso_academico;

            $this->planificacionEditRepo->updatePlanificacion($this->planificacionId, [
                'unidades' => $this->form->unidades,
                'proposito_unidad' => $this->form->proposito_unidad
            ]);
        } catch (\Exception $e) {
            // Silencioso - no interrumpir al usuario
        }
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
            if ($unidadesCompletas <= 1) {
                // Validación estricta para los primeros pasos
                $validator = $this->getUnidadValidator($this->openUnidad);
                
                if ($validator->fails()) {
                    $errors = $validator->errors()->all();
                    $msg = "No puedes avanzar a la Unidad " . ($targetIndex + 1) . " aún. Debes completar la Unidad " . ($this->openUnidad + 1) . ":\n\n• " . implode("\n• ", $errors);
                    $this->showAlert('error', $msg);
                    return;
                }
            } else {
                // Si ya tiene buen progreso, solo validamos si intenta ir a una unidad "nueva" (no alcanzada antes)
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
        // Aseguramos que los campos de lapso estén en el form
        $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
        $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
        $this->form->id_lapso_academico = $this->id_lapso_academico;

        // Pedimos solo las reglas de esta unidad específica
        $allRules = $this->form->rules($index);
        $rules = [];
        $messages = [];
        $attributes = [];

        foreach ($this->form->messages() as $key => $msg) {
            $messages["form.$key"] = $msg;
        }
        foreach ($this->form->validationAttributes() as $key => $attr) {
            $attributes["form.$key"] = $attr;
        }
        
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

    public function getTotalPonderacionForCorte($unidadIndex)
    {
        return $this->form->getTotalPonderacionForUnidad($unidadIndex);
    }

    public function saveProgress($index)
    {
        $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
        $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
        $this->form->id_lapso_academico = $this->id_lapso_academico;

        $success = $this->planificacionEditRepo->updatePlanificacion($this->planificacionId, [
            'unidades' => $this->form->unidades,
            'proposito_unidad' => $this->form->proposito_unidad
        ]);

        if ($success) {
            $this->showAlert('success', 'Progreso guardado exitosamente como borrador.', '/planificacion/list');
        } else {
            $this->showAlert('error', 'Error al guardar el progreso.');
        }
    }

    public function savePlanificacion()
    {
        try {
            $this->form->lapso_fecha_inicio = $this->lapso_fecha_inicio;
            $this->form->lapso_fecha_fin = $this->lapso_fecha_fin;
            $this->form->id_lapso_academico = $this->id_lapso_academico;

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

            $success = $this->planificacionEditRepo->updatePlanificacion($this->planificacionId, [
                'unidades' => $this->form->unidades,
                'proposito_unidad' => $this->form->proposito_unidad,
                'estatus' => '2'
            ]);

            // Actualizar la firma directamente en el modelo ya que el repo no lo maneja por defecto en su array base
            if ($success) {
                $draft = \App\Models\Planificacion::find($this->planificacionId);
                if ($draft) {
                    $draft->update(['id_firma_docente' => $firma->id_firma]);
                }
                $this->showAlert('success', '¡Guardado!, en espera que lo aprueben (puede verlo en la campana de notificaciones)', '/planificacion/list');
            } else {
                $this->showAlert('error', 'Error al actualizar la planificación.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "No se puede guardar. Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e; // Re-throw para que Livewire ponga las letras rojas
        }
    }

    public function toggleDetallesUnidad()
    {
        $this->mostrarDetallesUnidad = !$this->mostrarDetallesUnidad;
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
            $this->planificacionCreateRepo->saveNuevoObjetivo($this->newObjetivoNombre, $this->selectedTemaIdForObjetivo);

            // Recargar objetivos
            $this->todosLosObjetivos = $this->planificacionCreateRepo->select_objetivos($this->id_unidad_curricular);

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
            $this->planificacionCreateRepo->saveNuevaBibliografia($this->newBiblioNombre);

            // Recargar bibliografías
            $this->bibliografiasMaestras = $this->planificacionCreateRepo->select_bibliografias();

            session()->flash('message', 'Bibliografía creada correctamente.');
            $this->closeBiblioModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la bibliografía: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $contenidosData = $this->loadContenidosUnidad();

        return view('livewire.pages.planificacion.update-planificacion', [
            'timeSlots' => collect(range(8, 18))->map(fn($h) => sprintf('%02d:00', $h)),
            'temasPorUnidad' => $contenidosData['temasPorUnidad'],
            'todosLosContenidos' => $contenidosData['todosLosContenidos'],
            'todosLosObjetivos' => $contenidosData['todosLosObjetivos']
        ]);
    }
}


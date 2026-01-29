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

class UpdatePlanificacion extends Component
{

    protected $planificacionIndexRepo;
    protected $planificacionCreateRepo;
    protected $planificacionEditRepo;
    protected $planificacionViewRepo;

    // Datos principales de la planificación (NO EDITABLES, solo se muestran)
    public $planificacionId;
    public $docente_id;
    public $docente_nombre;
    public $docente_apellido;
    public $cedula;
    public $nombre_unidad_curricular;
    public $nombre_seccion;
    public $nombre_lapso;

    // Datos editables que vienen del formulario
    public $bibliografias = [];
    public $cortes = [];

    // Propiedades para listados de opciones
    public $recursosDisponibles = [];
    public $estrategiasDisponibles = [];
    public $contenidosDisponibles = [];
    public $indicadoresDisponibles = [];
    public $evaluacionesDisponibles = [];
    public $tecnicasDisponibles = [];
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

        if (Auth::id() !== $this->docente_id) {
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
        $this->nombre_unidad_curricular = $planificacion['nombre_unidad_curricular'];
        $this->nombre_seccion = $planificacion['nombre_seccion'];
        $this->nombre_lapso = $planificacion['nombre_lapso'];

        // Cargar contenidos disponibles filtrados por unidad
        $this->loadContenidosUnidad();

        // Cargar detalles dinámicos (cortes, bibliografías) desde el array del repositorio
        $this->bibliografias = collect($planificacion['bibliografias'])
            ->map(fn($item) => ['bibliografia_id' => $item['bibliografia_id']])
            ->toArray();

        $this->cortes = collect($planificacion['cortes'])
            ->map(function ($corte) {
                // Mapear los recursos
                $recursos = collect($corte['recursos'])
                    ->map(fn($r) => ['recurso_id' => $r['recurso_id']])
                    ->toArray();

                // Mapear las estrategias
                $estrategias = collect($corte['estrategias'])
                    ->map(fn($e) => ['estrategia_id' => $e['estrategia_id']])
                    ->toArray();

                // Mapear contenidos e indicadores
                $contenidos = collect($corte['contenidos'])
                    ->map(function ($cont) {
                    $indicadores = collect($cont['indicadores_logros'])
                        ->map(fn($ind) => ['indicador_id' => $ind['indicador_id']])
                        ->toArray();
                    return [
                        'contenido_id' => $cont['contenido_id'],
                        'indicadores_logros' => $indicadores,
                    ];
                })
                    ->toArray();

                // Mapear evaluaciones
                $evaluaciones = collect($corte['evaluaciones'])
                    ->map(fn($eval) => [
                        'evaluacion_id' => $eval['evaluacion_id'],
                        'tecnica_id' => $eval['tecnica_id'],
                        'ponderacion' => (float) $eval['ponderacion'],
                        'fecha_evaluacion' => $eval['fecha_evaluacion'],
                        'forma_participacion' => $eval['forma_participacion'],
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
                ];
            })
            ->toArray();

        // Inicializa los cortes si no hay ninguno para que el formulario se muestre correctamente
        if (empty($this->cortes)) {
            $this->addCorte();
        } else {
            // Asegúrate de que todos los arrays anidados estén inicializados para evitar errores de null
            foreach ($this->cortes as $corteIndex => $corte) {
                $this->cortes[$corteIndex]['recursos'] = $corte['recursos'] ?? [];
                $this->cortes[$corteIndex]['estrategias'] = $corte['estrategias'] ?? [];
                $this->cortes[$corteIndex]['contenidos'] = $corte['contenidos'] ?? [];
                foreach ($this->cortes[$corteIndex]['contenidos'] as $contenidoIndex => $contenido) {
                    $this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros'] = $contenido['indicadores_logros'] ?? [];
                }
                $this->cortes[$corteIndex]['evaluaciones'] = $corte['evaluaciones'] ?? [];
            }
        }
    }

    // Carga las opciones para los selects usando el repositorio
    private function loadDropdownOptions()
    {
        $this->recursosDisponibles = $this->planificacionCreateRepo->select_recursos()->toArray();
        $this->estrategiasDisponibles = $this->planificacionCreateRepo->select_estrategias()->toArray();
        $this->indicadoresDisponibles = $this->planificacionCreateRepo->select_indicadores()->toArray();
        $this->evaluacionesDisponibles = $this->planificacionCreateRepo->select_evaluaciones()->toArray();
        $this->tecnicasDisponibles = $this->planificacionCreateRepo->select_tecnicas()->toArray();
        $this->bibliografiasDisponibles = $this->planificacionCreateRepo->select_bibliografias()->toArray();
    }

    // Métodos específicos para añadir/eliminar cortes
    public function addCorte()
    {
        $this->cortes[] = [
            'corte' => count($this->cortes) + 1,
            'estatus' => 1, // Estatus inicial para un nuevo corte (podría ser un borrador)
            'recursos' => [],
            'estrategias' => [],
            'contenidos' => [],
            'evaluaciones' => [],
            'ultimo_motivo_rechazo' => null, // Nuevo corte no tiene motivo de rechazo
        ];
        // Al añadir un nuevo corte, asegúrate de añadir al menos un contenido y una evaluación
        $lastCorteIndex = count($this->cortes) - 1;
        $this->addItem($lastCorteIndex, 'contenidos');
        $this->addItem($lastCorteIndex, 'evaluaciones');
    }

    public function removeCorte($index)
    {
        unset($this->cortes[$index]);
        $this->cortes = array_values($this->cortes);
        // Reajustar los números de corte
        foreach ($this->cortes as $idx => $corte) {
            $this->cortes[$idx]['corte'] = $idx + 1;
        }
    }

    // Métodos genéricos para manejo de arrays dinámicos
    public function addItem($corteIndex, $arrayName, $contenidoIndex = null)
    {
        // Define templates por defecto sin el id
        $defaultTemplates = [
            'contenidos' => ['contenido_id' => '', 'indicadores_logros' => [['indicador_id' => '']]],
            'recursos' => ['recurso_id' => ''],
            'estrategias' => ['estrategia_id' => ''],
            'evaluaciones' => [
                'fecha_evaluacion' => '',
                'evaluacion_id' => '',
                'ponderacion' => 0,
                'tecnica_id' => '',
                'forma_participacion' => ''
            ],
            'indicadores_logros' => ['indicador_id' => ''],
            'bibliografias' => ['bibliografia_id' => ''],
        ];

        $template = $defaultTemplates[$arrayName] ?? [];

        if ($arrayName === 'bibliografias') {
            $this->bibliografias[] = $template;
        } elseif ($arrayName === 'indicadores_logros' && $contenidoIndex !== null) {
            if (isset($this->cortes[$corteIndex]['contenidos'][$contenidoIndex])) {
                $this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][] = $template;
            }
        } else { // Para recursos, estrategias, evaluaciones, contenidos (dentro de un corte)
            if (isset($this->cortes[$corteIndex])) { // Asegurarse de que el corte exista
                $this->cortes[$corteIndex][$arrayName][] = $template;
            }
        }
    }

    public function removeItem($corteIndex, $arrayName, $itemIndex, $contenidoIndex = null)
    {
        if ($arrayName === 'bibliografias') {
            if (isset($this->bibliografias[$itemIndex])) {
                unset($this->bibliografias[$itemIndex]);
                $this->bibliografias = array_values($this->bibliografias);
            }
        } elseif ($arrayName === 'indicadores_logros' && $contenidoIndex !== null) {
            if (isset($this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][$itemIndex])) {
                unset($this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][$itemIndex]);
                $this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros'] = array_values($this->cortes[$corteIndex]['contenidos'][$contenidoIndex]['indicadores_logros']);
            }
        } else { // Para contenidos, recursos, estrategias, evaluaciones (dentro de un corte)
            if (isset($this->cortes[$corteIndex][$arrayName][$itemIndex])) {
                unset($this->cortes[$corteIndex][$arrayName][$itemIndex]);
                $this->cortes[$corteIndex][$arrayName] = array_values($this->cortes[$corteIndex][$arrayName]);
            }
        }
    }


    protected function loadContenidosUnidad()
    {
        $this->contenidosDisponibles = $this->planificacionCreateRepo->select_contenidos($this->id_unidad_curricular)->toArray();
    }

    public function toggleDetallesUnidad()
    {
        $this->mostrarDetallesUnidad = !$this->mostrarDetallesUnidad;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function getTotalPonderacionForCorte($corteIndex)
    {
        return collect($this->cortes[$corteIndex]['evaluaciones'])
            ->sum(fn($e) => (float) ($e['ponderacion'] ?? 0));
    }

    // Reglas de validación
    public function rules()
    {
        $rules = [
            'bibliografias' => 'array',
            'cortes' => 'required|array|min:1',
        ];

        // Reglas para bibliografías
        foreach ($this->bibliografias as $biblioIndex => $bibliografia) {
            $rules["bibliografias.$biblioIndex.bibliografia_id"] = [
                'required',
                'exists:bibliografia,id_bibliografia',
                function ($attribute, $value, $fail) use ($biblioIndex) {
                    $allBiblioIds = collect($this->bibliografias)->pluck('bibliografia_id')->filter()->all();
                    $currentValueCount = 0;
                    foreach ($allBiblioIds as $i => $id) {
                        if ($id == $value && $i != $biblioIndex) {
                            $currentValueCount++;
                        }
                    }
                    if ($currentValueCount > 0) {
                        $fail('Esta bibliografía ya ha sido seleccionada.');
                    }
                },
            ];
        }

        foreach ($this->cortes as $index => $corte) {
            // Validación para recursos
            $rules["cortes.$index.recursos"] = 'array';
            foreach ($corte['recursos'] as $recursoIndex => $recurso) {
                $rules["cortes.$index.recursos.$recursoIndex.recurso_id"] = [
                    'required',
                    'exists:recurso,id_recurso',
                    function ($attribute, $value, $fail) use ($corte, $recursoIndex) {
                        $recursoIdsInCorte = collect($corte['recursos'])->pluck('recurso_id')->filter()->all();
                        $currentValueCount = 0;
                        foreach ($recursoIdsInCorte as $i => $id) {
                            if ($id == $value && $i != $recursoIndex) {
                                $currentValueCount++;
                            }
                        }
                        if ($currentValueCount > 0) {
                            $fail('Este recurso ya fue seleccionado en este corte.');
                        }
                    }
                ];
            }

            // Validación para estrategias
            $rules["cortes.$index.estrategias"] = 'array';
            foreach ($corte['estrategias'] as $estrategiaIndex => $estrategia) {
                $rules["cortes.$index.estrategias.$estrategiaIndex.estrategia_id"] = [
                    'required',
                    'exists:estrategia_pedagogica,id_estrategia_pedagogica',
                    function ($attribute, $value, $fail) use ($corte, $estrategiaIndex) {
                        $estrategiaIdsInCorte = collect($corte['estrategias'])->pluck('estrategia_id')->filter()->all();
                        $currentValueCount = 0;
                        foreach ($estrategiaIdsInCorte as $i => $id) {
                            if ($id == $value && $i != $estrategiaIndex) {
                                $currentValueCount++;
                            }
                        }
                        if ($currentValueCount > 0) {
                            $fail('Esta estrategia ya fue seleccionada en este corte.');
                        }
                    }
                ];
            }

            // Validación para contenidos
            $rules["cortes.$index.contenidos"] = 'array|min:1';
            $allContenidoIdsInForm = collect($this->cortes)->pluck('contenidos')
                ->flatten(1)
                ->pluck('contenido_id')
                ->filter()
                ->all();

            foreach ($corte['contenidos'] as $contenidoIndex => $contenido) {
                $rules["cortes.$index.contenidos.$contenidoIndex.contenido_id"] = [
                    'required',
                    'exists:tema,id_tema',
                    function ($attribute, $value, $fail) use ($allContenidoIdsInForm, $index, $contenidoIndex) {
                        $filteredContenidoIds = [];
                        foreach ($this->cortes as $cIdx => $corteItem) {
                            foreach ($corteItem['contenidos'] as $contIdx => $contItem) {
                                if (!($cIdx === $index && $contIdx === $contenidoIndex)) {
                                    $filteredContenidoIds[] = $contItem['contenido_id'];
                                }
                            }
                        }
                        if (in_array($value, $filteredContenidoIds)) {
                            $fail('Este contenido ya fue seleccionado en la planificación.');
                        }
                    }
                ];

                // Validación para Indicadores de Logro
                $rules["cortes.$index.contenidos.$contenidoIndex.indicadores_logros"] = 'required|array|min:1';
                if (isset($contenido['indicadores_logros']) && is_array($contenido['indicadores_logros'])) {
                    foreach ($contenido['indicadores_logros'] as $indicadorIndex => $indicador) {
                        $rules["cortes.$index.contenidos.$contenidoIndex.indicadores_logros.$indicadorIndex.indicador_id"] = [
                            'required',
                            'exists:indicador_logro,id_indicador_logro',
                        ];
                    }
                }
            }

            // Validación para evaluaciones
            $rules["cortes.$index.evaluaciones"] = 'array|min:1';
            foreach ($corte['evaluaciones'] as $evaluacionIndex => $evaluacion) {
                $fechaEvaluacionRules = ['required', 'date'];

                if ($this->lapso_fecha_inicio && $this->lapso_fecha_fin) {
                    $fechaEvaluacionRules[] = 'after_or_equal:' . $this->lapso_fecha_inicio;
                    $fechaEvaluacionRules[] = 'before_or_equal:' . $this->lapso_fecha_fin;
                }
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion"] = $fechaEvaluacionRules;
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.evaluacion_id"] = 'required|exists:evaluacion,id_evaluacion';
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.tecnica_id"] = 'required|exists:tecnica,id_tecnica';

                $rules["cortes.$index.evaluaciones.$evaluacionIndex.ponderacion"] = [
                    'required',
                    'numeric',
                    'min:1',
                    'max:25',
                    function ($attribute, $value, $fail) use ($index, $corte, $evaluacionIndex) {
                        $totalEvaluaciones = count($corte['evaluaciones']);
                        $currentPonderacionValue = (float) $value;

                        // Caso de una sola evaluación
                        if ($totalEvaluaciones === 1) {
                            if ($currentPonderacionValue != 25) {
                                $fail('La única evaluación en este corte debe tener 25% de ponderación.');
                            }
                        } elseif ($totalEvaluaciones > 1) {
                            // Calcular la suma de las otras ponderaciones en el mismo corte
                            $tempEvaluaciones = $this->cortes[$index]['evaluaciones'];
                            $sumaSinCampoActual = 0;
                            foreach ($tempEvaluaciones as $i => $eval) {
                                if ($i !== $evaluacionIndex) {
                                    $sumaSinCampoActual += (float) ($eval['ponderacion'] ?? 0);
                                }
                            }

                            $maxPermitido = 25 - $sumaSinCampoActual;

                            if ($currentPonderacionValue > $maxPermitido) {
                                $fail("La ponderación máxima permitida para esta evaluación es {$maxPermitido}%. (Suma de otras ponderaciones en este corte: {$sumaSinCampoActual}%)");
                            }
                        }
                    }
                ];

                $rules["cortes.$index.evaluaciones.$evaluacionIndex.forma_participacion"] = 'required|in:1,2,3';
            }

            // Validación final para la suma total de ponderaciones por corte
            if (!empty($corte['evaluaciones'])) {
                $rules["cortes.$index.total_ponderacion_corte"] = [
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForCorte($index);
                        if (abs($total - 25.0) > 0.001) { // Usar una tolerancia para comparación de punto flotante
                            $fail("La suma total de ponderaciones en el Corte " . ($index + 1) . " debe ser exactamente 25% (actual: {$total}%)");
                        }
                    }
                ];
            }
        }
        return $rules;
    }

    // Mensajes de validación personalizados
    public function messages()
    {
        return [
            'bibliografias.*.bibliografia_id.required' => 'La bibliografía es obligatoria.',
            'bibliografias.*.bibliografia_id.exists' => 'La bibliografía seleccionada no es válida.',
            'cortes.required' => 'La planificación debe tener al menos un corte.',
            'cortes.array' => 'Los cortes deben ser un array.',
            'cortes.min' => 'La planificación debe tener al menos un corte.',
            'cortes.*.recursos.*.recurso_id.required' => 'El recurso es obligatorio.',
            'cortes.*.recursos.*.recurso_id.exists' => 'El recurso seleccionado no es válido.',
            'cortes.*.estrategias.*.estrategia_id.required' => 'La estrategia es obligatoria.',
            'cortes.*.estrategias.*.estrategia_id.exists' => 'La estrategia seleccionada no es válida.',
            'cortes.*.contenidos.required' => 'Cada corte debe tener al menos un contenido.',
            'cortes.*.contenidos.array' => 'Los contenidos deben ser un array.',
            'cortes.*.contenidos.min' => 'Cada corte debe tener al menos un contenido.',
            'cortes.*.contenidos.*.contenido_id.required' => 'El contenido es obligatorio.',
            'cortes.*.contenidos.*.contenido_id.exists' => 'El contenido seleccionado no es válido.',
            'cortes.*.contenidos.*.indicadores_logros.required' => 'Debe seleccionar al menos un indicador de logro para este contenido.',
            'cortes.*.contenidos.*.indicadores_logros.array' => 'Los indicadores de logro deben ser un array.',
            'cortes.*.contenidos.*.indicadores_logros.min' => 'Debe seleccionar al menos un indicador de logro para este contenido.',
            'cortes.*.contenidos.*.indicadores_logros.*.indicador_id.required' => 'El indicador de logro es obligatorio.',
            'cortes.*.contenidos.*.indicadores_logros.*.indicador_id.exists' => 'El indicador de logro seleccionado no es válido.',
            'cortes.*.evaluaciones.required' => 'Cada corte debe tener al menos una evaluación.',
            'cortes.*.evaluaciones.array' => 'Las evaluaciones deben ser un array.',
            'cortes.*.evaluaciones.min' => 'Cada corte debe tener al menos una evaluación.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.required' => 'La fecha de evaluación es obligatoria.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.date' => 'La fecha de evaluación no es válida.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.after_or_equal' => 'La fecha debe ser después o igual al inicio del lapso (' . $this->lapso_fecha_inicio . ').',
            'cortes.*.evaluaciones.*.fecha_evaluacion.before_or_equal' => 'La fecha debe ser antes o igual al fin del lapso (' . $this->lapso_fecha_fin . ').',
            'cortes.*.evaluaciones.*.evaluacion_id.required' => 'El tipo de evaluación es obligatorio.',
            'cortes.*.evaluaciones.*.evaluacion_id.exists' => 'El tipo de evaluación seleccionado no es válido.',
            'cortes.*.evaluaciones.*.tecnica_id.required' => 'La técnica de evaluación es obligatoria.',
            'cortes.*.evaluaciones.*.tecnica_id.exists' => 'La técnica de evaluación seleccionada no es válida.',
            'cortes.*.evaluaciones.*.ponderacion.required' => 'La ponderación es obligatoria.',
            'cortes.*.evaluaciones.*.ponderacion.numeric' => 'La ponderación debe ser un número.',
            'cortes.*.evaluaciones.*.ponderacion.min' => 'La ponderación debe ser al menos :min.',
            'cortes.*.evaluaciones.*.forma_participacion.required' => 'La forma de participación es obligatoria.',
            'cortes.*.evaluaciones.*.forma_participacion.in' => 'La forma de participación no es válida.',
            'cortes.*.total_ponderacion_corte' => 'La suma total de ponderaciones en el corte es incorrecta.',
        ];
    }

    // Método para guardar los cambios
    public function savePlanificacion()
    {
        $this->validate();

        $success = $this->planificacionEditRepo->updatePlanificacion($this->planificacionId, [
            'bibliografias' => $this->bibliografias,
            'cortes' => $this->cortes
        ]);

        if ($success) {
            $data = ['tipo' => 'exitoso', 'color' => 'green', 'mensaje' => 'Planificación actualizada exitosamente.'];
            $this->dispatch('mostrar-mensaje', $data);
            return redirect()->to('/planificacion/list');
        } else {
            $data = ['tipo' => 'error', 'color' => 'red', 'mensaje' => 'Error al actualizar la planificación.'];
            $this->dispatch('mostrar-mensaje', $data);
        }
    }

    public function render()
    {
        return view('livewire.pages.planificacion.update-planificacion');
    }
}

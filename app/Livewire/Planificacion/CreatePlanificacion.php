<?php

namespace App\Livewire\Planificacion;

use App\Repositories\Planificacion\PlanificacionCreateRepo;
use Illuminate\Support\{Collection, Facades\Auth, Facades\DB, Str};
use Livewire\Component;
use Carbon\Carbon;

class CreatePlanificacion extends Component
{
    public $docente_id, $docenteNombre, $id_profesor_asignado, $proposito;
    public Collection $tecnicas, $recursosMaestros, $evaluaciones, $indicadores, $estrategiasMaestras, $bibliografiasMaestras, $asignaciones;
    public array $unidades = [];
    public array $temasPorUnidad = [];
    protected $planificacionRepository;

    public array $bibliografias = [['bibliografia_id' => '']];

    public Collection $contenidosPorTema;
    public Collection $todosLosContenidos;

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

        $this->loadInitialData();
        $this->verifyDocenteRole();
        $this->inicializarUnidades();
    }

    public function updatedIdProfesorAsignado($value)
    {
        if ($value) {
            // Buscar la asignación seleccionada para obtener el ID de la unidad curricular
            $asignacion = $this->asignaciones->firstWhere('id_detalle_profesor_asignado', $value);

            if ($asignacion) {
                // Obtener ID de unidad y sección desde la base de datos para asegurar integridad
                $detalle = DB::table('detalle_profesor_asignado')->where('id_detalle_profesor_asignado', $value)->first();
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

                    // Obtener propósito de la unidad curricular
                    $unidad = DB::table('unidad_curricular')->where('id_unidad_curricular', $detalle->id_unidad_curricular)->first();
                    if ($unidad) {
                        $this->proposito = $unidad->proposito_unidad_curricular;
                    }
                }
            }
        } else {
            $this->temasPorUnidad = [];
            $this->todosLosContenidos = collect();
            $this->proposito = '';
        }

        // Reiniciar los contenidos seleccionados en las unidades porque cambiaron las opciones disponibles
        $this->inicializarUnidades();
    }

    protected function loadInitialData()
    {
        $this->tecnicas = $this->planificacionRepository->select_tecnicas();
        $this->evaluaciones = $this->planificacionRepository->select_evaluaciones();
        $this->indicadores = $this->planificacionRepository->select_indicadores();
        $this->recursosMaestros = $this->planificacionRepository->select_recursos();
        $this->estrategiasMaestras = $this->planificacionRepository->select_estrategias();
        $this->bibliografiasMaestras = $this->planificacionRepository->select_bibliografias();

        // Cargar asignaciones del docente
        $this->asignaciones = $this->planificacionRepository->getAsignacionesDocente($this->docente_id);
    }

    public function refreshMasterLists($data)
    {
        switch ($data['tableName']) {
            case 'recurso':
                $this->recursosMaestros = $this->planificacionRepository->select_recursos();
                break;
            case 'estrategia_pedagogica':
                $this->estrategiasMaestras = $this->planificacionRepository->select_estrategias();
                break;
            case 'tecnica':
                $this->tecnicas = $this->planificacionRepository->select_tecnicas();
                break;
            case 'evaluacion':
                $this->evaluaciones = $this->planificacionRepository->select_evaluaciones();
                break;
            case 'indicador_logro':
                $this->indicadores = $this->planificacionRepository->select_indicadores();
                break;
            case 'bibliografia':
                $this->bibliografiasMaestras = $this->planificacionRepository->select_bibliografias();
                break;
        }
    }

    protected function verifyDocenteRole()
    {
        // Allow both Coordinador (1) and Docente (2)
        if (
            Auth::check() && (
                DB::table('usuario_rol')->where('id_users', Auth::id())->where('id_rol', 1)->exists() ||
                DB::table('usuario_rol')->where('id_users', Auth::id())->where('id_rol', 2)->exists()
            )
        ) {
            $this->docenteNombre = Auth::user()->name . ' ' . Auth::user()->apellido;
        } else {
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Acceso denegado.']);
        }
    }

    protected function inicializarUnidades()
    {
        foreach (range(0, 3) as $index) {
            $this->unidades[$index] = $this->createUnidadTemplate($index + 1);
        }
    }

    public function rules()
    {
        $rules = [
            'id_profesor_asignado' => 'required|exists:detalle_profesor_asignado,id_detalle_profesor_asignado',
        ];

        // INDICADORES DE LOGRO
        $allIndicadorIds = [];
        foreach ($this->unidades as $unidad) {
            if (isset($unidad['contenidos']) && is_array($unidad['contenidos'])) {
                foreach ($unidad['contenidos'] as $contenido) {
                    if (isset($contenido['indicadores_logros']) && is_array($contenido['indicadores_logros'])) {
                        $allIndicadorIds = array_merge($allIndicadorIds, array_column($contenido['indicadores_logros'], 'indicador_id'));
                    }
                }
            }
        }
        $allIndicadorIds = array_filter($allIndicadorIds);

        // Reglas para bibliografías
        foreach ($this->bibliografias as $biblioIndex => $bibliografia) {
            $rules["bibliografias.$biblioIndex.bibliografia_id"] = [
                'required',
                'exists:bibliografia,id_bibliografia',
                function ($attribute, $value, $fail) use ($biblioIndex) {
                    $allBiblioIds = collect($this->bibliografias)->pluck('bibliografia_id')->filter()->all();
                    $occurrences = array_keys($allBiblioIds, $value);
                    if (count($occurrences) > 1) {
                        $fail('Esta bibliografía ya ha sido seleccionada.');
                    }
                },
            ];
        }

        foreach ($this->unidades as $index => $unidad) {
            // Validación para recursos
            $recursoIds = array_column($unidad['recursos'], 'recurso_id');
            foreach ($unidad['recursos'] as $recursoIndex => $recurso) {
                $rules["unidades.$index.recursos.$recursoIndex.recurso_id"] = [
                    'required',
                    'exists:recurso,id_recurso',
                    function ($attribute, $value, $fail) use ($recursoIds, $recursoIndex) {
                        if (count(array_keys($recursoIds, $value)) > 1) {
                            $fail('Este recurso ya fue seleccionado en esta unidad.');
                        }
                    }
                ];
            }

            // Validación para estrategias
            $estrategiaIds = array_column($unidad['estrategias'], 'estrategia_id');
            foreach ($unidad['estrategias'] as $estrategiaIndex => $estrategia) {
                $rules["unidades.$index.estrategias.$estrategiaIndex.estrategia_id"] = [
                    'required',
                    'exists:estrategia_pedagogica,id_estrategia_pedagogica',
                    function ($attribute, $value, $fail) use ($estrategiaIds, $estrategiaIndex) {
                        if (count(array_keys($estrategiaIds, $value)) > 1) {
                            $fail('Esta estrategia ya fue seleccionada en esta unidad.');
                        }
                    }
                ];
            }

            // Validación para contenidos
            $contenidoIds = array_column($this->unidades, 'contenidos');
            $contenidoIds = array_merge(...array_map(function ($unidad) {
                return array_column($unidad['contenidos'], 'contenido_id');
            }, $this->unidades));
            foreach ($unidad['contenidos'] as $contenidoIndex => $contenido) {
                $rules["unidades.$index.contenidos.$contenidoIndex.indicadores_logros"] = 'required|array|min:1';
                $rules["unidades.$index.contenidos.$contenidoIndex.contenido_id"] = [
                    'required',
                    'exists:tema,id_tema',
                    function ($attribute, $value, $fail) use ($contenidoIds, $contenidoIndex) {
                        if (count(array_keys($contenidoIds, $value)) > 1) {
                            $fail('Este contenido ya fue seleccionado.');
                        }
                    }
                ];

                if (isset($contenido['indicadores_logros']) && is_array($contenido['indicadores_logros'])) {
                    foreach ($contenido['indicadores_logros'] as $indicadorIndex => $indicador) {
                        $rules["unidades.$index.contenidos.$contenidoIndex.indicadores_logros.$indicadorIndex.indicador_id"] = [
                            'required',
                            'exists:indicador_logro,id_indicador_logro',
                        ];
                    }
                }
            }

            // Validación para evaluaciones
            foreach ($unidad['evaluaciones'] as $evaluacionIndex => $evaluacion) {
                $fechaEvaluacionRules = ['required', 'date'];

                $rules["unidades.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion"] = $fechaEvaluacionRules;
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.evaluacion_id"] = 'required|exists:evaluacion,id_evaluacion';
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.tecnica_id"] = 'required|exists:tecnica,id_tecnica';

                $rules["unidades.$index.evaluaciones.$evaluacionIndex.ponderacion"] = [
                    'required',
                    'numeric',
                    'min:1',
                    'max:25',
                    function ($attribute, $value, $fail) use ($index, $unidad, $evaluacionIndex) {
                        $totalEvaluaciones = count($unidad['evaluaciones']);
                        $sumaActual = $this->getTotalPonderacionForUnidad($index);

                        if ($totalEvaluaciones === 1 && $value != 25) {
                            $fail('La única evaluación debe tener 25% de ponderación');
                        } elseif ($totalEvaluaciones > 1) {
                            $sumaSinActual = $sumaActual - ($unidad['evaluaciones'][$evaluacionIndex]['ponderacion'] ?? 0);
                            $maxPermitido = 25 - $sumaSinActual;

                            if ($value > $maxPermitido) {
                                $fail("La ponderación máxima permitida para esta evaluación es $maxPermitido% (Suma actual sin este campo: $sumaSinActual%)");
                            }
                        }
                    }
                ];

                $rules["unidades.$index.evaluaciones.$evaluacionIndex.forma_participacion"] = 'required|in:1,2,3';
            }

            // VALIDACIÓN PARA OBJETIVOS
            foreach ($unidad['objetivos'] as $objetivoIndex => $objetivo) {
                $rules["unidades.$index.objetivos.$objetivoIndex.nombre_objetivo"] = 'required|string|min:5|max:255';
            }

            $rules["unidades.$index.evaluaciones.$evaluacionIndex.ponderacion"][] = function ($attribute, $value, $fail) use ($index) {
                $total = $this->getTotalPonderacionForUnidad($index);
                if ($total < 25) {
                    $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " debe ser al menos 25% (actual: {$total}%)");
                } elseif ($total > 25) {
                    $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " debe ser exactamente 25% (actual: {$total}%)");
                }
            };
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages['id_profesor_asignado.required'] = 'Debe seleccionar una asignación (Materia y Sección).';
        $messages['id_profesor_asignado.exists'] = 'La asignación seleccionada no es válida.';

        // Mensajes personalizados para arrays anidados
        // Mensajes personalizados para arrays anidados
        $messages['unidades.*.recursos.*.recurso_id.required'] = 'Debe seleccionar un recurso.';
        $messages['unidades.*.estrategias.*.estrategia_id.required'] = 'Debe seleccionar una estrategia.';

        $messages['unidades.*.contenidos.*.contenido_id.required'] = 'Debe seleccionar un contenido.';
        $messages['unidades.*.contenidos.*.indicadores_logros.*.indicador_id.required'] = 'Debe seleccionar un indicador de logro.';

        $messages['unidades.*.evaluaciones.*.fecha_evaluacion.required'] = 'La fecha de evaluación es obligatoria.';
        $messages['unidades.*.evaluaciones.*.fecha_evaluacion.date'] = 'La fecha de evaluación no es válida.';
        $messages['unidades.*.evaluaciones.*.evaluacion_id.required'] = 'Debe seleccionar el tipo de evaluación.';
        $messages['unidades.*.evaluaciones.*.tecnica_id.required'] = 'Debe seleccionar una técnica de evaluación.';
        $messages['unidades.*.evaluaciones.*.ponderacion.required'] = 'La ponderación es obligatoria.';
        $messages['unidades.*.evaluaciones.*.ponderacion.numeric'] = 'La ponderación debe ser un número.';
        $messages['unidades.*.evaluaciones.*.forma_participacion.required'] = 'Debe seleccionar una forma de participación.';
        $messages['unidades.*.evaluaciones.*.forma_participacion.in'] = 'La forma de participación seleccionada no es válida.';

        $messages['unidades.*.objetivos.*.nombre_objetivo.required'] = 'El nombre del objetivo es obligatorio.';
        $messages['unidades.*.objetivos.*.nombre_objetivo.min'] = 'El objetivo debe tener al menos 5 caracteres.';

        $messages['bibliografias.*.bibliografia_id.required'] = 'Debe seleccionar una referencia bibliográfica.';
        $messages['bibliografias.*.bibliografia_id.exists'] = 'La referencia bibliográfica seleccionada no es válida.';

        // Mensajes genéricos de respaldo
        $messages['*.required'] = 'El campo es obligatorio.';
        $messages['*.min'] = 'El campo debe tener al menos :min caracteres.';
        $messages['*.numeric'] = 'El campo debe ser un número.';
        $messages['*.in'] = 'El valor seleccionado para el campo no es válido.';
        $messages['*.exists'] = 'El valor seleccionado no existe en la base de datos.';

        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function createUnidadTemplate($numero)
    {
        return [
            'numero' => $numero,
            'objetivos' => [['nombre_objetivo' => '']],
            'contenidos' => [['tema_id' => '', 'contenido_id' => '', 'indicadores_logros' => [['indicador_id' => '']]]],
            'recursos' => [['recurso_id' => '']],
            'estrategias' => [['estrategia_id' => '']],
            'evaluaciones' => [['fecha_evaluacion' => '', 'evaluacion_id' => '', 'ponderacion' => 0, 'tecnica_id' => '', 'forma_participacion' => '']]
        ];
    }

    public function addItem($unidadIndex, $arrayName, $contenidoIndex = null)
    {
        $defaultTemplates = [
            'contenidos' => ['tema_id' => '', 'contenido_id' => '', 'indicadores_logros' => [['indicador_id' => '']]],
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
            'objetivos' => ['nombre_objetivo' => ''],
        ];

        $template = $defaultTemplates[$arrayName] ?? [];

        if ($arrayName === 'bibliografias') {
            $this->bibliografias[] = $template;
        } elseif ($arrayName === 'indicadores_logros' && $contenidoIndex !== null) {
            if (isset($this->unidades[$unidadIndex]['contenidos'][$contenidoIndex])) {
                $this->unidades[$unidadIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][] = $template;
            }
        } else {
            $this->unidades[$unidadIndex][$arrayName][] = $template;
        }
    }

    public function removeItem($unidadIndex, $arrayName, $itemIndex, $contenidoIndex = null)
    {
        if ($arrayName === 'contenidos') {
            unset($this->unidades[$unidadIndex][$arrayName][$itemIndex]);
            $this->unidades[$unidadIndex][$arrayName] = array_values($this->unidades[$unidadIndex][$arrayName]);
        } elseif ($arrayName === 'indicadores_logros' && $contenidoIndex !== null) {
            if (isset($this->unidades[$unidadIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][$itemIndex])) {
                unset($this->unidades[$unidadIndex]['contenidos'][$contenidoIndex]['indicadores_logros'][$itemIndex]);
                $this->unidades[$unidadIndex]['contenidos'][$contenidoIndex]['indicadores_logros'] = array_values($this->unidades[$unidadIndex]['contenidos'][$contenidoIndex]['indicadores_logros']);
            }
        } elseif ($arrayName === 'bibliografias') {
            if (isset($this->bibliografias[$itemIndex])) {
                unset($this->bibliografias[$itemIndex]);
                $this->bibliografias = array_values($this->bibliografias);
            }
        } elseif ($arrayName === 'objetivos') {
            unset($this->unidades[$unidadIndex][$arrayName][$itemIndex]);
            $this->unidades[$unidadIndex][$arrayName] = array_values($this->unidades[$unidadIndex][$arrayName]);
        } else {
            unset($this->unidades[$unidadIndex][$arrayName][$itemIndex]);
            $this->unidades[$unidadIndex][$arrayName] = array_values($this->unidades[$unidadIndex][$arrayName]);
        }
    }

    public function getTotalPonderacionForUnidad($unidadIndex)
    {
        return collect($this->unidades[$unidadIndex]['evaluaciones'])
            ->sum(fn($e) => (float) ($e['ponderacion'] ?? 0));
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
        $this->validate();

        DB::beginTransaction();

        try {
            $planificacionData = [
                'id_profesor_asignado' => $this->id_profesor_asignado,
                'fecha_creacion' => now(),
                'estatus' => '2', // Pendiente por defecto
            ];

            $planificacionId = DB::table('planificacion')->insertGetId($planificacionData);

            // Nota: Aquí se elimina la actualización del propósito en unidad curricular
            // ya que ahora seleccionamos una asignación establecida.

            foreach ($this->unidades as $unidad) {
                $unidadData = [
                    'id_planificacion' => $planificacionId,
                    'numero_unidad' => $unidad['numero'],
                    'fecha_creacion' => now(),
                    'estatus' => '2',
                ];
                $unidadId = DB::table('unidad')->insertGetId($unidadData);

                foreach ($unidad['objetivos'] as $objetivo) {
                    if (!empty($objetivo['nombre_objetivo'])) {
                        DB::table('objetivo')->insert([
                            'id_unidad' => $unidadId, // Sincronizado con BD fisica
                            'nombre_objetivo' => $objetivo['nombre_objetivo'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                    }
                }

                foreach ($unidad['recursos'] as $recurso) {
                    if (!empty($recurso['recurso_id'])) {
                        DB::table('detalle_recurso')->insert([
                            'id_unidad' => $unidadId,
                            'id_recurso' => $recurso['recurso_id'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                    }
                }

                foreach ($unidad['estrategias'] as $estrategia) {
                    if (!empty($estrategia['estrategia_id'])) {
                        DB::table('detalle_estrategia_pedagogica')->insert([
                            'id_unidad' => $unidadId,
                            'id_estrategia_pedagogica' => $estrategia['estrategia_id'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                    }
                }

                foreach ($unidad['contenidos'] as $contenido) {
                    if (!empty($contenido['contenido_id'])) {
                        // Guardar en detalle_contenido (Relación Corte -> Contenido)
                        // Aseguramos que se guarde el ID del Contenido seleccionado
                        $detalleContenidoId = DB::table('detalle_contenido')->insertGetId([
                            'id_unidad' => $unidadId,
                            'id_contenido' => $contenido['contenido_id'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);

                        // Para indicadores:
                        foreach ($contenido['indicadores_logros'] as $indicador) {
                            if (!empty($indicador['indicador_id'])) {
                                DB::table('detalle_indicador')->insert([
                                    'id_detalle_contenido' => $detalleContenidoId, // Vinculo con el contenido planificado
                                    'id_indicador_logro' => $indicador['indicador_id'],
                                    'fecha_creacion' => now(),
                                    'estatus' => '1',
                                ]);
                            }
                        }
                    }
                }

                foreach ($unidad['evaluaciones'] as $evaluacion) {
                    if (!empty($evaluacion['evaluacion_id'])) {
                        DB::table('detalle_evaluacion')->insert([
                            'id_unidad' => $unidadId,
                            'id_evaluacion' => $evaluacion['evaluacion_id'],
                            'id_tecnica' => $evaluacion['tecnica_id'],
                            'ponderacion_detalle_evaluacion' => $evaluacion['ponderacion'],
                            'fecha_evaluacion_detalle_evaluacion' => $evaluacion['fecha_evaluacion'],
                            'forma_participacion_detalle_evaluacion' => $evaluacion['forma_participacion'],
                            'fecha_creacion' => now(),
                            'estatus' => '1', // Nuevo estatus default
                        ]);
                    }
                }
            }

            foreach ($this->bibliografias as $bibliografia) {
                if (!empty($bibliografia['bibliografia_id'])) {
                    DB::table('detalle_bibliografia')->insert([
                        'id_planificacion' => $planificacionId,
                        'id_bibliografia' => $bibliografia['bibliografia_id'],
                        'fecha_creacion' => now(),
                        'estatus' => '1',
                    ]);
                }
            }

            DB::commit();
            $this->dispatch('mostrar-mensaje', ['tipo' => 'exitoso', 'mensaje' => 'Planificación guardada correctamente.']);
            $this->reset(['unidades', 'bibliografias', 'id_profesor_asignado']);
            $this->inicializarUnidades();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('mostrar-mensaje', ['tipo' => 'error', 'mensaje' => 'Error al guardar la planificación: ' . $e->getMessage()]);
        }
    }
}

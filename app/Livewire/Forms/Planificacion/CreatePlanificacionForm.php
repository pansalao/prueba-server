<?php

namespace App\Livewire\Forms\Planificacion;

use Illuminate\Support\Facades\Auth;

use Livewire\Form;

class CreatePlanificacionForm extends Form
{
    public $id_profesor_asignado;
    public $unidades = [];

    public function getTotalPonderacionForUnidad($unidadIndex)
    {
        return collect($this->unidades[$unidadIndex]['evaluaciones'])
            ->sum(fn($e) => (float) ($e['ponderacion'] ?? 0));
    }

    public function rules()
    {
        $rules = [
            'id_profesor_asignado' => 'required|exists:detalle_profesor_asignado,id_detalle_profesor_asignado',
        ];

        foreach ($this->unidades as $index => $unidad) {
            // Validación para estrategias
            foreach ($unidad['estrategias'] as $estIndex => $estrategia) {
                $rules["unidades.$index.estrategias.$estIndex.tema_id"] = 'required|exists:tema_unidad,id_tema_unidad';
                $rules["unidades.$index.estrategias.$estIndex.actividad"] = 'required|string|min:5';

                foreach ($estrategia['recursos'] as $recIndex => $recurso) {
                    $rules["unidades.$index.estrategias.$estIndex.recursos.$recIndex.recurso_id"] = 'required|exists:recurso,id_recurso';
                }
            }

            // Validación para objetivos y contenidos
            $contenidoIds = [];
            foreach ($unidad['objetivos'] as $obj) {
                foreach ($obj['contenidos'] as $cont) {
                    $contenidoIds[] = $cont['contenido_id'];
                }
            }

            foreach ($unidad['objetivos'] as $objIndex => $objetivo) {
                $rules["unidades.$index.objetivos.$objIndex.tema_id"] = 'required|exists:tema_unidad,id_tema_unidad';
                $rules["unidades.$index.objetivos.$objIndex.objetivo_id"] = 'required|exists:objetivo,id_objetivo';

                foreach ($objetivo['contenidos'] as $contIndex => $contenido) {
                    $rules["unidades.$index.objetivos.$objIndex.contenidos.$contIndex.contenido_id"] = [
                        'required',
                        'exists:contenido,id_contenido',
                        function ($attribute, $value, $fail) use ($contenidoIds) {
                            if (count(array_keys($contenidoIds, $value)) > 1) {
                                $fail('Este contenido ya fue seleccionado.');
                            }
                        }
                    ];
                }
            }

            // Validación para evaluaciones
            foreach ($unidad['evaluaciones'] as $evaluacionIndex => $evaluacion) {
                $fechaEvaluacionRules = [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if (!$this->id_profesor_asignado)
                            return;

                        $lapso = \Illuminate\Support\Facades\DB::table('detalle_profesor_asignado as dpa')
                            ->join('seccion as s', 'dpa.id_seccion', '=', 's.id_seccion')
                            ->join('lapso_academico as la', 's.id_lapso_academico', '=', 'la.id_lapso_academico')
                            ->where('dpa.id_detalle_profesor_asignado', $this->id_profesor_asignado)
                            ->select('la.fecha_inicio_lapso_academico', 'la.fecha_fin_lapso_academico', 'la.id_lapso_academico')
                            ->first();

                        if ($lapso) {
                            if ($value < $lapso->fecha_inicio_lapso_academico || $value > $lapso->fecha_fin_lapso_academico) {
                                $fail("La fecha de evaluación debe estar dentro del lapso académico ({$lapso->fecha_inicio_lapso_academico} al {$lapso->fecha_fin_lapso_academico}).");
                            }

                            $evento = \Illuminate\Support\Facades\DB::table('evento as e')
                                ->where('e.id_lapso', $lapso->id_lapso_academico)
                                ->where(function ($q) use ($value) {
                                    $q->whereDate('e.dia_inicio_evento', '<=', $value)
                                        ->whereDate('e.dia_fin_evento', '>=', $value);
                                })
                                ->select('e.descripcion_evento')
                                ->first();

                            if ($evento) {
                                $fail("No se puede asignar una evaluación en esta fecha debido al evento: {$evento->descripcion_evento}.");
                            }
                        }
                    }
                ];
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion"] = $fechaEvaluacionRules;
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.evaluacion_id"] = 'required|exists:tipo_evaluacion,id_tipo_evaluacion';
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.tecnica_id"] = 'required|exists:tecnica_evaluacion,id_tecnica_evaluacion';
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.ponderacion"] = [
                    'bail',
                    'required',
                    'integer',
                    'min:5',
                    'max:25',
                    function ($attribute, $value, $fail) use ($index, $unidad, $evaluacionIndex) {
                        $totalEvaluaciones = count($unidad['evaluaciones']);
                        if ($totalEvaluaciones === 1 && (int) $value !== 25) {
                            $fail('La única evaluación debe tener exactamente 25% de ponderación.');
                        }
                    },
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForUnidad($index);
                        if ($total > 25) {
                            $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " no puede superar el 25% (actual: {$total}%)");
                        }
                    }
                ];

                $rules["unidades.$index.evaluaciones.$evaluacionIndex.forma_participacion"] = 'required|in:1,2';

                // Validate 'integrantes' only if forma_participacion is GRUPAL (2)
                if (isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2') {
                    $rules["unidades.$index.evaluaciones.$evaluacionIndex.integrantes"] = 'required|integer|min:2|max:10';
                }
            }

            // Validación para bibliografías
            foreach ($unidad['bibliografias'] as $bibIndex => $biblio) {
                $rules["unidades.$index.bibliografias.$bibIndex.bibliografia_id"] = 'required|exists:bibliografia,id_bibliografia';
            }

            $rules["unidades.$index.indicadores_logro"] = 'required|string|min:5';

            $rules["unidades.$index.total_ponderacion_check"] = [
                function ($attribute, $value, $fail) use ($index) {
                    $total = $this->getTotalPonderacionForUnidad($index);
                    if ($total != 25) {
                        $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " debe ser exactamente 25% (actual: {$total}%)");
                    }
                }
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'unidades.*.indicadores_logro.required' => 'Los indicadores de logro son obligatorios.',
            'unidades.*.indicadores_logro.min' => 'Los indicadores de logro deben tener al menos 5 caracteres.',
        ];

        $messages['id_profesor_asignado.required'] = 'Debe seleccionar una Unidad Curricular.';
        $messages['id_profesor_asignado.exists'] = 'La asignación seleccionada no es válida.';

        $messages['unidades.*.recursos.*.recurso_id.required'] = 'Debe seleccionar un recurso.';
        $messages['unidades.*.objetivos.*.tema_id.required'] = 'Debe seleccionar un tema.';
        $messages['unidades.*.objetivos.*.objetivo_id.required'] = 'Debe seleccionar un objetivo.';
        $messages['unidades.*.objetivos.*.contenidos.*.contenido_id.required'] = 'Debe seleccionar un contenido.';

        $messages['unidades.*.estrategias.*.tema_id.required'] = 'Debe seleccionar un tema para la estrategia.';
        $messages['unidades.*.estrategias.*.actividad.required'] = 'La descripción de la actividad es obligatoria.';
        $messages['unidades.*.estrategias.*.actividad.min'] = 'La actividad debe tener al menos 5 caracteres.';
        $messages['unidades.*.estrategias.*.recursos.*.recurso_id.required'] = 'Debe seleccionar un recurso.';

        $messages['unidades.*.evaluaciones.*.fecha_evaluacion.required'] = 'La fecha de evaluación es obligatoria.';
        $messages['unidades.*.evaluaciones.*.fecha_evaluacion.date'] = 'La fecha de evaluación no es válida.';
        $messages['unidades.*.evaluaciones.*.evaluacion_id.required'] = 'Debe seleccionar el tipo de evaluación.';
        $messages['unidades.*.evaluaciones.*.tecnica_id.required'] = 'Debe seleccionar una técnica de evaluación.';
        $messages['unidades.*.evaluaciones.*.ponderacion.required'] = 'La ponderación es obligatoria.';
        $messages['unidades.*.evaluaciones.*.ponderacion.integer'] = 'La ponderación debe ser un número entero.';
        $messages['unidades.*.evaluaciones.*.ponderacion.min'] = 'La ponderación mínima es 5%.';
        $messages['unidades.*.evaluaciones.*.ponderacion.max'] = 'La ponderación máxima es 25%.';
        $messages['unidades.*.evaluaciones.*.integrantes.required'] = 'Debe indicar el número de integrantes para evaluaciones grupales.';
        $messages['unidades.*.evaluaciones.*.integrantes.min'] = 'El grupo debe tener al menos 2 integrantes.';
        $messages['unidades.*.evaluaciones.*.integrantes.max'] = 'El grupo no puede exceder los 10 integrantes.';
        $messages['unidades.*.evaluaciones.*.forma_participacion.required'] = 'Debe seleccionar una forma de participación.';
        $messages['unidades.*.evaluaciones.*.forma_participacion.in'] = 'La forma de participación seleccionada no es válida.';
        $messages['unidades.*.bibliografias.*.bibliografia_id.required'] = 'Debe seleccionar una referencia bibliográfica.';

        $messages['*.required'] = 'El campo es obligatorio.';
        $messages['*.min'] = 'El campo debe tener al menos :min caracteres.';
        $messages['*.numeric'] = 'El campo debe ser un número.';
        $messages['*.in'] = 'El valor seleccionado para el campo no es válido.';
        $messages['*.exists'] = 'El valor seleccionado no existe en la base de datos.';

        return $messages;
    }
}


<?php

namespace App\Livewire\Forms\Planificacion;

use Livewire\Form;

class UpdatePlanificacionForm extends Form
{
    public $bibliografias = [];
    public $cortes = [];
    public $lapso_fecha_inicio;
    public $lapso_fecha_fin;
    public $id_lapso_academico;

    public function getTotalPonderacionForCorte($corteIndex)
    {
        return collect($this->cortes[$corteIndex]['evaluaciones'])
            ->sum(fn($e) => (float) ($e['ponderacion'] ?? 0));
    }

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
                $rules["cortes.$index.estrategias.$estrategiaIndex.tema_id"] = [
                    'required',
                    'exists:tema_unidad,id_tema_unidad',
                    function ($attribute, $value, $fail) use ($corte, $estrategiaIndex) {
                        $temaIdsInCorte = collect($corte['estrategias'])->pluck('tema_id')->filter()->all();
                        $currentValueCount = 0;
                        foreach ($temaIdsInCorte as $i => $id) {
                            if ($id == $value && $i != $estrategiaIndex) {
                                $currentValueCount++;
                            }
                        }
                        if ($currentValueCount > 0) {
                            $fail('Este tema ya fue seleccionado en este corte.');
                        }
                    }
                ];
            }

            // Validación para contenidos
            $rules["cortes.$index.contenidos"] = 'array|min:1';
            foreach ($corte['contenidos'] as $contenidoIndex => $contenido) {
                $rules["cortes.$index.contenidos.$contenidoIndex.contenido_id"] = [
                    'required',
                    'exists:contenido,id_contenido',
                    function ($attribute, $value, $fail) use ($index, $contenidoIndex) {
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
            }

            // Validación para evaluaciones
            $rules["cortes.$index.evaluaciones"] = 'array|min:1';
            foreach ($corte['evaluaciones'] as $evaluacionIndex => $evaluacion) {
                $fechaEvaluacionRules = [
                    'required',
                    'date',
                    function ($attribute, $value, $fail) {
                        if ($this->lapso_fecha_inicio && $this->lapso_fecha_fin) {
                            if ($value < $this->lapso_fecha_inicio || $value > $this->lapso_fecha_fin) {
                                $fail("La fecha de evaluación debe estar dentro del lapso académico ({$this->lapso_fecha_inicio} al {$this->lapso_fecha_fin}).");
                            }
                        }

                        if ($this->id_lapso_academico) {
                            $evento = \Illuminate\Support\Facades\DB::table('evento as e')
                                ->where('e.id_lapso', $this->id_lapso_academico)
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
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion"] = $fechaEvaluacionRules;
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.evaluacion_id"] = 'required|exists:tipo_evaluacion,id_tipo_evaluacion';
                $rules["cortes.$index.evaluaciones.$evaluacionIndex.tecnica_id"] = 'required|exists:tecnica_evaluacion,id_tecnica_evaluacion';

                $rules["cortes.$index.evaluaciones.$evaluacionIndex.ponderacion"] = [
                    'bail',
                    'required',
                    'integer',
                    'min:5',
                    'max:25',
                    function ($attribute, $value, $fail) use ($index, $corte, $evaluacionIndex) {
                        $totalEvaluaciones = count($corte['evaluaciones']);
                        if ($totalEvaluaciones === 1 && (int) $value !== 25) {
                            $fail('La única evaluación debe tener exactamente 25% de ponderación.');
                        }
                    },
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForCorte($index);
                        if ($total > 25) {
                            $fail("La suma total de ponderaciones en el Corte " . ($index + 1) . " no puede superar el 25% (actual: {$total}%)");
                        }
                    }
                ];

                $rules["cortes.$index.evaluaciones.$evaluacionIndex.forma_participacion"] = 'required|in:1,2';

                if (isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2') {
                    $rules["cortes.$index.evaluaciones.$evaluacionIndex.integrantes"] = 'required|integer|min:2|max:10';
                }
            }

            // Validación final para la suma total de ponderaciones por corte
            if (!empty($corte['evaluaciones'])) {
                $rules["cortes.$index.total_ponderacion_corte"] = [
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForCorte($index);
                        if (abs($total - 25.0) > 0.001) {
                            $fail("La suma total de ponderaciones en el Corte " . ($index + 1) . " debe ser exactamente 25% (actual: {$total}%)");
                        }
                    }
                ];
            }

            $rules["cortes.$index.indicadores_logro"] = 'required|string|min:5';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'bibliografias.*.bibliografia_id.required' => 'La bibliografía es obligatoria.',
            'bibliografias.*.bibliografia_id.exists' => 'La bibliografía seleccionada no es válida.',
            'cortes.required' => 'La planificación debe tener al menos un corte.',
            'cortes.min' => 'La planificación debe tener al menos un corte.',
            'cortes.*.recursos.*.recurso_id.required' => 'El recurso es obligatorio.',
            'cortes.*.recursos.*.recurso_id.exists' => 'El recurso seleccionado no es válido.',
            'cortes.*.estrategias.*.tema_id.required' => 'El tema de la estrategia es obligatorio.',
            'cortes.*.estrategias.*.tema_id.exists' => 'El tema seleccionado no es válido.',
            'cortes.*.contenidos.required' => 'Cada corte debe tener al menos un contenido.',
            'cortes.*.contenidos.min' => 'Cada corte debe tener al menos un contenido.',
            'cortes.*.contenidos.*.contenido_id.required' => 'El contenido es obligatorio.',
            'cortes.*.contenidos.*.contenido_id.exists' => 'El contenido seleccionado no es válido.',
            'cortes.*.evaluaciones.required' => 'Cada corte debe tener al menos una evaluación.',
            'cortes.*.evaluaciones.min' => 'Cada corte debe tener al menos una evaluación.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.required' => 'La fecha de evaluación es obligatoria.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.date' => 'La fecha de evaluación no es válida.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.after_or_equal' => 'La fecha debe estar dentro del lapso.',
            'cortes.*.evaluaciones.*.fecha_evaluacion.before_or_equal' => 'La fecha debe estar dentro del lapso.',
            'cortes.*.evaluaciones.*.evaluacion_id.required' => 'El tipo de evaluación es obligatorio.',
            'cortes.*.evaluaciones.*.evaluacion_id.exists' => 'El tipo de evaluación seleccionado no es válido.',
            'cortes.*.evaluaciones.*.tecnica_id.required' => 'La técnica de evaluación es obligatoria.',
            'cortes.*.evaluaciones.*.tecnica_id.exists' => 'La técnica de evaluación seleccionada no es válida.',
            'cortes.*.evaluaciones.*.ponderacion.required' => 'La ponderación es obligatoria.',
            'cortes.*.evaluaciones.*.ponderacion.integer' => 'La ponderación debe ser un número entero.',
            'cortes.*.evaluaciones.*.ponderacion.min' => 'La ponderación debe ser al menos 5%.',
            'cortes.*.evaluaciones.*.ponderacion.max' => 'La ponderación máxima es 25%.',
            'cortes.*.evaluaciones.*.forma_participacion.required' => 'La forma de participación es obligatoria.',
            'cortes.*.evaluaciones.*.forma_participacion.in' => 'La forma de participación no es válida.',
            'cortes.*.evaluaciones.*.integrantes.required' => 'Debe indicar el número de integrantes.',
            'cortes.*.evaluaciones.*.integrantes.min' => 'Mínimo 2 integrantes.',
            'cortes.*.evaluaciones.*.integrantes.max' => 'Máximo 10 integrantes.',
            'cortes.*.indicadores_logro.required' => 'Los indicadores de logro son obligatorios.',
            'cortes.*.indicadores_logro.min' => 'Mínimo 5 caracteres.',
        ];
    }
}

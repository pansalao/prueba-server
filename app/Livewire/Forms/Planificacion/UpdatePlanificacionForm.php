<?php

namespace App\Livewire\Forms\Planificacion;

use Livewire\Form;

class UpdatePlanificacionForm extends Form
{
    public $unidades = [];
    public $lapso_fecha_inicio;
    public $lapso_fecha_fin;
    public $id_lapso_academico;

    // Cache for performance
    protected $cachedEventos = null;

    public function getTotalPonderacionForUnidad($unidadIndex)
    {
        return collect($this->unidades[$unidadIndex]['evaluaciones'] ?? [])
            ->sum(fn($e) => (float) ($e['ponderacion'] ?? 0));
    }

    public function isUnidadComplete($index)
    {
        return $this->isTematicaComplete($index) &&
               $this->isEstrategiasComplete($index) &&
               $this->isIndicadoresComplete($index) &&
               $this->isEvaluacionComplete($index) &&
               $this->isBibliografiasComplete($index);
    }

    public function isTematicaComplete($index)
    {
        if (!isset($this->unidades[$index])) return false;
        $u = $this->unidades[$index];
        if (empty($u['objetivos'])) return false;
        foreach ($u['objetivos'] as $obj) {
            if (empty($obj['tema_id']) || empty($obj['objetivo_id'])) return false;
            if (empty($obj['contenidos'])) return false;
            foreach ($obj['contenidos'] as $cont) {
                if (empty($cont['contenido_id'])) return false;
            }
        }
        return true;
    }

    public function isEstrategiasComplete($index)
    {
        if (!isset($this->unidades[$index])) return false;
        $u = $this->unidades[$index];
        if (empty($u['estrategias'])) return false;
        foreach ($u['estrategias'] as $est) {
            if (empty($est['tecnica_actividad_id']) || empty($est['actividad'])) return false;
            if (empty($est['recursos'])) return false;
            foreach ($est['recursos'] as $rec) {
                if (empty($rec['recurso_id'])) return false;
            }
        }
        return true;
    }

    public function isIndicadoresComplete($index)
    {
        if (!isset($this->unidades[$index])) return false;
        $u = $this->unidades[$index];
        return !empty($u['indicadores_logro']) && strlen($u['indicadores_logro']) >= 5;
    }

    public function isEvaluacionComplete($index)
    {
        if (!isset($this->unidades[$index])) return false;
        $u = $this->unidades[$index];
        if (abs($this->getTotalPonderacionForUnidad($index) - 25) > 0.01) return false;
        if (empty($u['evaluaciones'])) return false;
        foreach ($u['evaluaciones'] as $eval) {
            if (empty($eval['fecha_evaluacion']) || empty($eval['evaluacion_id']) || 
                empty($eval['tecnica_id']) || empty($eval['forma_participacion'])) return false;
            
            // Si es grupal, verificar integrantes
            if ($eval['forma_participacion'] == '2' && (empty($eval['integrantes']) || $eval['integrantes'] < 2)) return false;
        }
        return true;
    }

    public function isBibliografiasComplete($index)
    {
        if (!isset($this->unidades[$index])) return false;
        $u = $this->unidades[$index];
        if (empty($u['bibliografias'])) return false;
        foreach ($u['bibliografias'] as $bib) {
            if (empty($bib['bibliografia_id'])) return false;
        }
        return true;
    }

    public function rules($unitIndex = null)
    {
        $rules = [
            'unidades' => 'required|array|min:1',
        ];

        $unidadesToValidate = ($unitIndex !== null) ? [$unitIndex => $this->unidades[$unitIndex]] : $this->unidades;

        foreach ($unidadesToValidate as $index => $unidad) {
            // Validación para estrategias
            foreach ($unidad['estrategias'] as $estIndex => $estrategia) {
                $rules["unidades.$index.estrategias.$estIndex.tema_id"] = 'nullable|exists:tema_unidad,id_tema_unidad'; // Ajustado porque esquema db usa tecnica_actividad
                $rules["unidades.$index.estrategias.$estIndex.tecnica_actividad_id"] = 'required|string';
                $rules["unidades.$index.estrategias.$estIndex.actividad"] = 'nullable|string|min:5';

                foreach ($estrategia['recursos'] as $recIndex => $recurso) {
                    $rules["unidades.$index.estrategias.$estIndex.recursos.$recIndex.recurso_id"] = 'required|string';
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
                        if ($this->lapso_fecha_inicio && $this->lapso_fecha_fin) {
                            if ($value < $this->lapso_fecha_inicio || $value > $this->lapso_fecha_fin) {
                                $fail("La fecha debe estar dentro del lapso ({$this->lapso_fecha_inicio} al {$this->lapso_fecha_fin}).");
                            }
                        }

                    }
                ];
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.fecha_evaluacion"] = $fechaEvaluacionRules;
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.evaluacion_id"] = 'required|string|min:1';
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.tecnica_id"] = 'required|string|min:1';
                $rules["unidades.$index.evaluaciones.$evaluacionIndex.ponderacion"] = [
                    'bail',
                    'required',
                    'integer',
                    'min:5',
                    'max:25',
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForUnidad($index);
                        if ($total > 25) {
                            $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " no puede superar el 25% (actual: {$total}%)");
                        }
                    }
                ];

                $rules["unidades.$index.evaluaciones.$evaluacionIndex.forma_participacion"] = 'required|in:1,2';

                if (isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2') {
                    $rules["unidades.$index.evaluaciones.$evaluacionIndex.integrantes"] = 'required|integer|min:2|max:10';
                }
            }

            // Validación para bibliografías
            foreach ($unidad['bibliografias'] as $bibIndex => $biblio) {
                $rules["unidades.$index.bibliografias.$bibIndex.bibliografia_id"] = 'required|string';
            }

            $rules["unidades.$index.indicadores_logro"] = 'nullable|string|min:5';

            if (!empty($unidad['evaluaciones'])) {
                $rules["unidades.$index.total_ponderacion_check"] = [
                    function ($attribute, $value, $fail) use ($index) {
                        $total = $this->getTotalPonderacionForUnidad($index);
                        if ($total != 25) {
                            $fail("La suma total de ponderaciones en la Unidad " . ($index + 1) . " debe ser exactamente 25% (actual: {$total}%)");
                        }
                    }
                ];
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'unidades.required' => 'La planificación debe tener al menos una unidad.',
            'unidades.min' => 'La planificación debe tener al menos una unidad.',
            'unidades.*.estrategias.*.tecnica_actividad_id.required' => 'Debe seleccionar una estrategia.',
            'unidades.*.estrategias.*.recursos.*.recurso_id.required' => 'El recurso es obligatorio.',
            'unidades.*.objetivos.*.tema_id.required' => 'El tema es obligatorio.',
            'unidades.*.objetivos.*.objetivo_id.required' => 'El objetivo es obligatorio.',
            'unidades.*.objetivos.*.contenidos.*.contenido_id.required' => 'El contenido es obligatorio.',
            'unidades.*.evaluaciones.*.fecha_evaluacion.required' => 'La fecha de evaluación es obligatoria.',
            'unidades.*.evaluaciones.*.fecha_evaluacion.date' => 'La fecha de evaluación no es válida.',
            'unidades.*.evaluaciones.*.evaluacion_id.required' => 'El tipo de evaluación es obligatorio.',
            'unidades.*.evaluaciones.*.tecnica_id.required' => 'La técnica de evaluación es obligatoria.',
            'unidades.*.evaluaciones.*.ponderacion.required' => 'La ponderación es obligatoria.',
            'unidades.*.evaluaciones.*.ponderacion.min' => 'La ponderación mínima es 5%.',
            'unidades.*.evaluaciones.*.ponderacion.max' => 'La ponderación máxima es 25%.',
            'unidades.*.evaluaciones.*.forma_participacion.required' => 'La forma de participación es obligatoria.',
            'unidades.*.evaluaciones.*.integrantes.required' => 'Debe indicar el número de integrantes.',
            'unidades.*.bibliografias.*.bibliografia_id.required' => 'La referencia bibliográfica es obligatoria.',
            'unidades.*.indicadores_logro.min' => 'Mínimo 5 caracteres.',
        ];
    }

    public function validationAttributes()
    {
        $attributes = [];

        foreach ($this->unidades as $index => $unidad) {
            $uNum = $index + 1;
            $attributes["unidades.$index.indicadores_logro"] = "Indicadores de Logro (Unidad $uNum)";
            
            foreach ($unidad['objetivos'] as $objIndex => $obj) {
                $oNum = $objIndex + 1;
                $attributes["unidades.$index.objetivos.$objIndex.tema_id"] = "Tema $oNum (Unidad $uNum)";
                $attributes["unidades.$index.objetivos.$objIndex.objetivo_id"] = "Objetivo $oNum (Unidad $uNum)";
                foreach ($obj['contenidos'] as $contIndex => $cont) {
                    $cNum = $contIndex + 1;
                    $attributes["unidades.$index.objetivos.$objIndex.contenidos.$contIndex.contenido_id"] = "Contenido $cNum del Objetivo $oNum (Unidad $uNum)";
                }
            }

            foreach ($unidad['estrategias'] as $estIndex => $est) {
                $eNum = $estIndex + 1;
                $attributes["unidades.$index.estrategias.$estIndex.tecnica_actividad_id"] = "Técnica de la Estrategia $eNum (Unidad $uNum)";
                $attributes["unidades.$index.estrategias.$estIndex.actividad"] = "Descripción de la Actividad $eNum (Unidad $uNum)";
                foreach ($est['recursos'] as $recIndex => $rec) {
                    $rNum = $recIndex + 1;
                    $attributes["unidades.$index.estrategias.$estIndex.recursos.$recIndex.recurso_id"] = "Recurso $rNum de la Estrategia $eNum (Unidad $uNum)";
                }
            }

            foreach ($unidad['evaluaciones'] as $evalIndex => $eval) {
                $evNum = $evalIndex + 1;
                $attributes["unidades.$index.evaluaciones.$evalIndex.fecha_evaluacion"] = "Fecha de la Evaluación $evNum (Unidad $uNum)";
                $attributes["unidades.$index.evaluaciones.$evalIndex.evaluacion_id"] = "Tipo de la Evaluación $evNum (Unidad $uNum)";
                $attributes["unidades.$index.evaluaciones.$evalIndex.tecnica_id"] = "Técnica de la Evaluación $evNum (Unidad $uNum)";
                $attributes["unidades.$index.evaluaciones.$evalIndex.ponderacion"] = "Ponderación de la Evaluación $evNum (Unidad $uNum)";
                $attributes["unidades.$index.evaluaciones.$evalIndex.forma_participacion"] = "Forma de Participación $evNum (Unidad $uNum)";
                $attributes["unidades.$index.evaluaciones.$evalIndex.integrantes"] = "N° de Integrantes $evNum (Unidad $uNum)";
            }

            foreach ($unidad['bibliografias'] as $bibIndex => $bib) {
                $bNum = $bibIndex + 1;
                $attributes["unidades.$index.bibliografias.$bibIndex.bibliografia_id"] = "Referencia Bibliográfica $bNum (Unidad $uNum)";
            }
        }

        return $attributes;
    }
}

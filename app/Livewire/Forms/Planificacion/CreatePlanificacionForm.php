<?php

namespace App\Livewire\Forms\Planificacion;

use Illuminate\Support\Facades\Auth;

use Livewire\Form;

class CreatePlanificacionForm extends Form
{
    public $id_profesor_asignado;
    public $unidades = [];
    public $tipos_seccion = [];
    public $proposito_unidad;
    
    // Cache for performance
    protected $cachedLapso = null;
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

    public function areObjetivosFilled($index) {
        $u = $this->unidades[$index] ?? null;
        if (!$u || empty($u['objetivos'])) return false;
        foreach ($u['objetivos'] as $obj) {
            if (empty($obj['tema_id']) || empty($obj['objetivo_id'])) return false;
            if (empty($obj['contenidos'])) return false;
            foreach ($obj['contenidos'] as $cont) {
                if (empty($cont['contenido_id'])) return false;
            }
        }
        return true;
    }

    public function areContenidosFilled($index, $objIndex) {
        $obj = $this->unidades[$index]['objetivos'][$objIndex] ?? null;
        if (!$obj || empty($obj['contenidos'])) return false;
        foreach ($obj['contenidos'] as $cont) {
            if (empty($cont['contenido_id'])) return false;
        }
        return true;
    }

    public function areEstrategiasFilled($index) {
        $u = $this->unidades[$index] ?? null;
        if (!$u || empty($u['estrategias'])) return false;
        foreach ($u['estrategias'] as $est) {
            if (empty($est['tecnica_actividad_id']) || empty($est['actividad']) || strlen($est['actividad']) < 5) return false;
            if (empty($est['recursos'])) return false;
            foreach ($est['recursos'] as $rec) {
                if (empty($rec['recurso_id'])) return false;
            }
        }
        return true;
    }

    public function areRecursosFilled($index, $estIndex) {
        $est = $this->unidades[$index]['estrategias'][$estIndex] ?? null;
        if (!$est || empty($est['recursos'])) return false;
        foreach ($est['recursos'] as $rec) {
            if (empty($rec['recurso_id'])) return false;
        }
        return true;
    }

    public function areEvaluacionesFilled($index) {
        $u = $this->unidades[$index] ?? null;
        if (!$u || empty($u['evaluaciones'])) return false;
        foreach ($u['evaluaciones'] as $eval) {
            if (empty($eval['fecha_evaluacion']) || empty($eval['evaluacion_id']) || 
                empty($eval['tecnica_id']) || empty($eval['forma_participacion'])) return false;
            if ($eval['forma_participacion'] == '2' && (empty($eval['integrantes']) || $eval['integrantes'] < 2)) return false;
            if (empty($eval['ponderacion']) || $eval['ponderacion'] < 5) return false;
        }
        return true;
    }

    public function areBibliografiasFilled($index) {
        $u = $this->unidades[$index] ?? null;
        if (!$u || empty($u['bibliografias'])) return false;
        foreach ($u['bibliografias'] as $bib) {
            if (empty($bib['bibliografia_id'])) return false;
        }
        return true;
    }

    public function rules($unitIndex = null)
    {
        $rules = [
            'id_profesor_asignado' => 'required',
            'tipos_seccion' => 'required|array|min:1',
            'proposito_unidad' => 'required|string|min:5',
        ];

        $unidadesToValidate = ($unitIndex !== null) ? [$unitIndex => $this->unidades[$unitIndex]] : $this->unidades;

        foreach ($unidadesToValidate as $index => $unidad) {
            // Validación para estrategias
            foreach ($unidad['estrategias'] as $estIndex => $estrategia) {
                $rules["unidades.$index.estrategias.$estIndex.tecnica_actividad_id"] = 'required|string';
                $rules["unidades.$index.estrategias.$estIndex.actividad"] = 'required|string|min:5';

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
                        if (!$this->id_profesor_asignado) return;

                        // Validar que no sea fin de semana
                        $dayOfWeek = \Carbon\Carbon::parse($value)->dayOfWeek;
                        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                            $fail("La fecha de evaluación no puede caer en fin de semana (sábado o domingo).");
                        }

                        if (!$this->cachedLapso) {
                            $this->cachedLapso = \Illuminate\Support\Facades\DB::connection('emulacion_sogac_2')
                                ->table('seccion_unidad_docente as sud')
                                ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
                                ->join('lapso_academico as la', 's.sec_cod_lapso_academico', '=', 'la.lap_codigo')
                                ->where('sud.sud_codigo', $this->id_profesor_asignado)
                                ->select('la.lap_fecha_inicio', 'la.lap_fecha_fin', 'la.lap_codigo')
                                ->first();
                        }

                        if ($this->cachedLapso) {
                            if ($value < $this->cachedLapso->lap_fecha_inicio || $value > $this->cachedLapso->lap_fecha_fin) {
                                $fail("La fecha debe estar dentro del lapso ({$this->cachedLapso->lap_fecha_inicio} al {$this->cachedLapso->lap_fecha_fin}).");
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

                // Validate 'integrantes' only if forma_participacion is GRUPAL (2)
                if (isset($evaluacion['forma_participacion']) && $evaluacion['forma_participacion'] == '2') {
                    $rules["unidades.$index.evaluaciones.$evaluacionIndex.integrantes"] = 'required|integer|min:2|max:10';
                }
            }

            // Validación para bibliografías
            foreach ($unidad['bibliografias'] as $bibIndex => $biblio) {
                $rules["unidades.$index.bibliografias.$bibIndex.bibliografia_id"] = 'required|string';
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
        $messages['tipos_seccion.required'] = 'Debe seleccionar al menos un tipo de sección (Regular o Repitencia).';
        $messages['tipos_seccion.min'] = 'Debe seleccionar al menos un tipo de sección (Regular o Repitencia).';

        $messages['unidades.*.recursos.*.recurso_id.required'] = 'Debe seleccionar un recurso.';
        $messages['unidades.*.objetivos.*.tema_id.required'] = 'Debe seleccionar un tema.';
        $messages['unidades.*.objetivos.*.objetivo_id.required'] = 'Debe seleccionar un objetivo.';
        $messages['unidades.*.objetivos.*.contenidos.*.contenido_id.required'] = 'Debe seleccionar un contenido.';

        $messages['unidades.*.estrategias.*.tecnica_actividad_id.required'] = 'Debe seleccionar una estrategia o actividad.';
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

    public function validationAttributes()
    {
        $attributes = [
            'id_profesor_asignado' => 'Unidad Curricular',
            'tipos_seccion' => 'Tipo de Sección',
        ];

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


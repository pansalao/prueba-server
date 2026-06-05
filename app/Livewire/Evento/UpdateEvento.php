<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\UpdateEventoForm;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Repositories\Evento\EventoUpdateRepo;
use App\Repositories\Evento\EventoViewRepo;
use Exception;

class UpdateEvento extends Component
{
    public UpdateEventoForm $form;
    public $eventosExistentes = [];
    public $justificacionesRequeridas = [];
    public $justificacionesGuardadas = [];
    protected $eventoRepository;
    protected $viewRepository;

    public function boot(EventoUpdateRepo $eventoRepo, EventoViewRepo $viewRepo)
    {
        $this->eventoRepository = $eventoRepo;
        $this->viewRepository = $viewRepo;
    }

    public function mount($id)
    {
        $evento = $this->viewRepository->mostrar($id);
        if (!$evento) {
            return redirect()->route('evento/listar')->with('error', 'Evento no encontrado.');
        }

        $this->form->setEvento($evento);
        if (!empty($this->form->justificativo_evento)) {
            $this->justificacionesGuardadas = $this->form->justificativo_evento;
        }
        $this->refreshEventos();
        if (empty($this->form->semanas)) {
            $this->form->semanas = [
                ['lapso' => 1, 'semana' => ''],
                ['lapso' => 2, 'semana' => ''],
            ];
        }

        $this->evaluarJustificacionesRequeridas();
    }

    public function refreshEventos()
    {
        $this->eventosExistentes = \App\Models\Evento::orderBy('nombre_evento')->get();
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);

        // 1. APLICAR TODA LA LÓGICA DINÁMICA DE ESTADO PRIMERO

        // Si cambia especial_evento y es Inicio (2) o Fin (3) del Lapso, aplicamos valores por defecto. Si es Vacaciones Colectivas (1) aplicamos los suyos.
        if ($propertyName === 'form.id_especial_evento') {
            if ($this->form->id_especial_evento == '2' || $this->form->id_especial_evento == '3') {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '1';
                $this->form->tipo_evento = '4';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 0;
            } elseif ($this->form->id_especial_evento == '13') {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '1';
                $this->form->tipo_evento = '4';
                $this->form->codigo_color_evento = '#007bff';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = false;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 0;
                $this->form->is_semana_evento = true;
                $this->form->semanas = [
                    ['lapso' => 1, 'semana' => '6'],
                    ['lapso' => 2, 'semana' => '6'],
                ];
            } elseif ($this->form->id_especial_evento == '7') {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '1';
                $this->form->tipo_evento = '4';
                $this->form->codigo_color_evento = '#007bff';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = false;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 0;
                $this->form->is_semana_evento = true;
                $this->form->semanas = [
                    ['lapso' => 1, 'semana' => '4'],
                    ['lapso' => 2, 'semana' => '4'],
                ];
            } elseif (in_array($this->form->id_especial_evento, ['8', '14'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '1';
                $this->form->tipo_evento = '4';
                $this->form->codigo_color_evento = '#007bff';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = false;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 0;
            } elseif (in_array($this->form->id_especial_evento, ['9', '10'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '1';
                $this->form->tipo_evento = '4';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 0;
            } elseif ($this->form->id_especial_evento == '1') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '';
                $this->form->tipo_evento = '5';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '60';
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 60;
            } elseif ($this->form->id_especial_evento == '11') { // Incorporación
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->cantidad_repetible_evento = '';
                $this->form->tipo_evento = '5';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->is_superponible = false;
                $this->form->is_semana_evento = false;
                $this->form->semanas = [];
                $this->form->cantidad_dias_evento = 0;
            } else {
                $this->form->cantidad_dias_evento = 0;
            }
            $nombresEspeciales = \App\Models\EspecialEvento::pluck('especial_evento_name', 'id_especial_evento')->toArray();

            if (isset($nombresEspeciales[$this->form->id_especial_evento])) {
                $this->form->descripcion_evento = $nombresEspeciales[$this->form->id_especial_evento];
            } else {
                $this->form->descripcion_evento = '';
            }
            
            if (!in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                $this->form->is_dia_evento = false;
                $this->form->dia_evento = null;
            }
        }

        // Si cambia cantidad_repetible_evento y is_semana_evento está activo, sincronizar semanas
        if ($propertyName === 'form.cantidad_repetible_evento' && $this->form->is_semana_evento) {
            $cuantas = max(1, (int) $this->form->cantidad_repetible_evento);
            if ($cuantas > 8) $cuantas = 8;
            
            $nuevasSemanas = [];
            // Mantener semanas existentes de Lapso 1 (recortar o extender)
            $semanasLapso1 = array_values(array_filter($this->form->semanas ?? [], fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 1));
            for ($i = 0; $i < $cuantas; $i++) {
                $nuevasSemanas[] = isset($semanasLapso1[$i]) ? $semanasLapso1[$i] : ['lapso' => 1, 'semana' => ''];
            }
            // Mantener semanas existentes de Lapso 2
            $semanasLapso2 = array_values(array_filter($this->form->semanas ?? [], fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == 2));
            for ($i = 0; $i < $cuantas; $i++) {
                $nuevasSemanas[] = isset($semanasLapso2[$i]) ? $semanasLapso2[$i] : ['lapso' => 2, 'semana' => ''];
            }
            
            $this->form->semanas = $nuevasSemanas;
        }

        // Si cambia is_especial
        if ($propertyName === 'form.is_especial' && $this->form->is_especial) {
            $this->form->is_independiente = true;
            $this->form->is_semana_evento = false;
        }

        // Si cambia el tipo de evento
        if ($propertyName === 'form.tipo_evento') {
            if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->is_semana_evento = false;
            } else {
                $this->form->is_independiente = false;
                $this->form->is_dia_evento = false;
                $this->form->dia_evento = null;
            }

            if (!in_array($this->form->id_especial_evento, ['1', '2', '3', '7', '8', '9', '10', '11', '13', '14'])) {
                if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = false;
                } else {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = true;
                }
            }
        }

        if ($propertyName === 'form.is_independiente' && $this->form->is_independiente) {
            $this->form->is_semana_evento = false;
        }

        if ($propertyName === 'form.is_repetible' && !$this->form->is_repetible) {
            $this->form->is_semana_evento = false;
            $this->form->cantidad_repetible_evento = '1';
        }

        // Si no es repetible, recortar a máximo 1 semana por lapso
        if (!$this->form->is_repetible && is_array($this->form->semanas)) {
            $nuevoSemanas = [];
            $has1 = false;
            $has2 = false;
            foreach ($this->form->semanas as $s) {
                $lapso = is_array($s) ? ($s['lapso'] ?? 1) : 1;
                if ($lapso == 1 && !$has1) {
                    $nuevoSemanas[] = $s;
                    $has1 = true;
                }
                if ($lapso == 2 && !$has2) {
                    $nuevoSemanas[] = $s;
                    $has2 = true;
                }
            }
            $this->form->semanas = $nuevoSemanas;
        }

        // Limpiar especial_evento si el switch se apaga
        if ($propertyName === 'form.is_especial' && !$this->form->is_especial) {
            $this->form->id_especial_evento = '';
            $this->form->cantidad_dias_evento = 0;
            $this->resetErrorBag('form.id_especial_evento');
            $this->resetErrorBag('form.cantidad_dias_evento');

            // Reestablecer valores por defecto según el tipo de evento actual
            if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->is_semana_evento = false;
            } else {
                $this->form->is_independiente = false;
                $this->form->is_laborable = false;
                $this->form->is_repetible = true;
                $this->form->is_superponible = false;
            }
            $this->form->is_rango_dias = false;
            $this->form->rango_dias = '';
        }

        // Limpiar rango de días si el switch se apaga
        if ($propertyName === 'form.is_rango_dias' && !$this->form->is_rango_dias) {
            $this->form->rango_dias = '';
            $this->resetErrorBag('form.rango_dias');
        }

        // Si se activa is_semana_evento, generar semanas según cantidad_repetible
        if ($propertyName === 'form.is_semana_evento' && $this->form->is_semana_evento) {
            $cuantas = max(1, (int) $this->form->cantidad_repetible_evento);
            if ($cuantas > 8) $cuantas = 8;
            $nuevasSemanas = [];
            for ($i = 0; $i < $cuantas; $i++) {
                $nuevasSemanas[] = ['lapso' => 1, 'semana' => ''];
            }
            for ($i = 0; $i < $cuantas; $i++) {
                $nuevasSemanas[] = ['lapso' => 2, 'semana' => ''];
            }
            $this->form->semanas = $nuevasSemanas;
        }

        // Si se desactiva is_semana_evento, limpiar todas las semanas
        if ($propertyName === 'form.is_semana_evento' && !$this->form->is_semana_evento) {
            $this->form->semanas = [];
            $this->resetErrorBag('form.semanas');
        }

        // 2. FINALMENTE VALIDAMOS EL CAMPO
        $this->form->validateOnly($field);

        $this->evaluarJustificacionesRequeridas();
    }

    public function evaluarJustificacionesRequeridas()
    {
        $viejas = $this->justificacionesRequeridas;
        $this->justificacionesRequeridas = [];

        if ($this->form->id_especial_evento == '13' && $this->form->is_semana_evento) {
            foreach ($this->form->semanas as $semana) {
                if (isset($semana['semana']) && $semana['semana'] != '' && $semana['semana'] != '6') {
                    $lapsoText = "Lapso " . ($semana['lapso'] ?? 1);
                    $titulo = $lapsoText;

                    $encontrado = false;
                    $textoPrevio = '';
                    foreach($viejas as $v) {
                        if ($v['titulo'] == $titulo && $v['lapso'] == ($semana['lapso'] ?? 1)) {
                            $textoPrevio = $v['texto'] ?? '';
                            $encontrado = true;
                            break;
                        }
                    }
                    if (!$encontrado) {
                        foreach ($this->justificacionesGuardadas as $guardada) {
                            if (($guardada['periodo'] == $titulo || str_contains($guardada['periodo'], $titulo)) && $guardada['lapso'] == ($semana['lapso'] ?? 1)) {
                                $textoPrevio = $guardada['texto'] ?? '';
                                break;
                            }
                        }
                    }

                    $this->justificacionesRequeridas[] = [
                        'titulo' => $titulo,
                        'lapso' => $semana['lapso'] ?? 1,
                        'mensaje' => "El Inicio del P.E.R. tiene configurada la semana {$semana['semana']}. Justifique por qué es diferente a la semana 6.",
                        'texto' => $textoPrevio,
                        'dato_colocado' => $semana['semana'],
                        'dato_esperado' => '6'
                    ];
                }
            }
        } elseif ($this->form->id_especial_evento == '7' && $this->form->is_semana_evento) {
            foreach ($this->form->semanas as $semana) {
                if (isset($semana['semana']) && $semana['semana'] != '' && $semana['semana'] != '4') {
                    $lapsoText = "Lapso " . ($semana['lapso'] ?? 1);
                    $titulo = $lapsoText;

                    $encontrado = false;
                    $textoPrevio = '';
                    foreach($viejas as $v) {
                        if ($v['titulo'] == $titulo && $v['lapso'] == ($semana['lapso'] ?? 1)) {
                            $textoPrevio = $v['texto'] ?? '';
                            $encontrado = true;
                            break;
                        }
                    }
                    if (!$encontrado) {
                        foreach ($this->justificacionesGuardadas as $guardada) {
                            if (($guardada['periodo'] == $titulo || str_contains($guardada['periodo'], $titulo)) && $guardada['lapso'] == ($semana['lapso'] ?? 1)) {
                                $textoPrevio = $guardada['texto'] ?? '';
                                break;
                            }
                        }
                    }

                    $this->justificacionesRequeridas[] = [
                        'titulo' => $titulo,
                        'lapso' => $semana['lapso'] ?? 1,
                        'mensaje' => "El Inicio del Trayecto Inicial tiene configurada la semana {$semana['semana']}. Justifique por qué es diferente a la semana 4.",
                        'texto' => $textoPrevio,
                        'dato_colocado' => $semana['semana'],
                        'dato_esperado' => '4'
                    ];
                }
            }
        }
    }

    public function guardar()
    {
        try {
            if (!$this->form->is_semana_evento) {
                $this->form->semanas = [];
            }
            $this->form->validate();

            $this->evaluarJustificacionesRequeridas();

            if (count($this->justificacionesRequeridas) > 0) {
                foreach ($this->justificacionesRequeridas as $req) {
                    $texto = trim($req['texto'] ?? '');
                    if (empty($texto)) {
                        $this->showAlert('error', 'Debe llenar todas las justificaciones requeridas para continuar.');
                        return;
                    }
                    if (mb_strlen($texto) < 5) {
                        $this->showAlert('error', "La justificación para '{$req['titulo']}' debe tener al menos 5 caracteres.");
                        return;
                    }
                }

                $this->justificacionesGuardadas = array_map(function($req) {
                    return [
                        'texto' => trim($req['texto']),
                        'periodo' => $req['titulo'],
                        'lapso' => $req['lapso'],
                        'dato_colocado' => $req['dato_colocado'] ?? null,
                        'dato_esperado' => $req['dato_esperado'] ?? null,
                    ];
                }, $this->justificacionesRequeridas);
            } else {
                $this->justificacionesGuardadas = [];
            }

            $this->ejecutarGuardar();

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar evento: ' . $e->getMessage());
        }
    }

    public function ejecutarGuardar()
    {
        try {
            $this->form->justificativo_evento = !empty($this->justificacionesGuardadas) ? $this->justificacionesGuardadas : null;

            $this->eventoRepository->actualizar($this->form->id_evento, $this->form->all());
            $this->showAlert('success', 'Evento actualizado correctamente.', '/evento/list');
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar evento: ' . $e->getMessage());
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }
    public function cancelar()
    {
        return redirect()->route('evento/listar');
    }

    public function agregarSemana($lapso = 1)
    {
        if ($this->form->is_repetible) {
            $semanasLapso = array_filter($this->form->semanas ?? [], fn($s) => (is_array($s) ? ($s['lapso'] ?? 1) : 1) == $lapso);
            if (count($semanasLapso) >= 4) {
                return;
            }
            $this->form->semanas[] = ['lapso' => (int) $lapso, 'semana' => ''];
        }
    }

    public function removerSemana($index)
    {
        unset($this->form->semanas[$index]);
        $this->form->semanas = array_values($this->form->semanas);
    }

    #[Computed]
    public function eventosEspecialesUsados()
    {
        return \Illuminate\Support\Facades\DB::table('evento')
            ->whereNotNull('id_especial_evento')
            ->where('id_evento', '!=', $this->form->id_evento)
            ->pluck('id_especial_evento')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pages.evento.edit-evento');
    }
}

<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\CreateEventoForm;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Repositories\Evento\EventoCreateRepo;
use Exception;

class CreateEvento extends Component
{
    public CreateEventoForm $form;
    public $eventosExistentes = [];
    protected $eventoRepository;

    public function boot(EventoCreateRepo $repo)
    {
        $this->eventoRepository = $repo;
    }

    public function mount()
    {
        $this->refreshEventos();
        if (empty($this->form->semanas)) {
            $this->form->semanas = [
                ['lapso' => 1, 'semana' => ''],
                ['lapso' => 2, 'semana' => ''],
            ];
        }
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
                $this->form->tipo_evento = '4';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = true;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 1;
            } elseif (in_array($this->form->id_especial_evento, ['7', '8', '13', '14'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->tipo_evento = '4';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = false;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 1;
            } elseif (in_array($this->form->id_especial_evento, ['9', '10'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '4';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 1;

            } elseif ($this->form->id_especial_evento == '1') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = true;
                $this->form->tipo_evento = '5';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 60;
            } elseif ($this->form->id_especial_evento == '4') { // Semana Santa
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->is_dia_evento = false;
                $this->form->cantidad_dias_evento = 2;
            } elseif ($this->form->id_especial_evento == '5') { // Carnaval
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_cantidad_dias_evento = true;
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->is_dia_evento = false;
                $this->form->cantidad_dias_evento = 2;
            } elseif ($this->form->id_especial_evento == '11') { // Incorporación
                $this->form->tipo_evento = '5';
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->is_superponible = false;
                $this->form->is_cantidad_dias_evento = true;
                $this->form->cantidad_dias_evento = 1;
                $this->form->is_semana_evento = false;
                $this->form->semanas = [];
            } else {
                $this->form->cantidad_dias_evento = 0;
            }
            $nombresEspeciales = \App\Models\EspecialEvento::pluck('especial_evento_name', 'id_especial_evento')->toArray();

            if (isset($nombresEspeciales[$this->form->id_especial_evento])) {
                $this->form->descripcion_evento = $nombresEspeciales[$this->form->id_especial_evento];
            } else {
                $this->form->descripcion_evento = '';
            }
        }

        // Si cambia is_especial
        if ($propertyName === 'form.is_especial' && $this->form->is_especial) {
            $this->form->is_independiente = true;
            $this->form->is_semana_evento = false;
        }

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

            if (!in_array($this->form->id_especial_evento, ['1', '2', '3', '4', '5', '7', '8', '9', '10', '11', '13', '14'])) {
                if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = false;
                    $this->form->is_cantidad_dias_evento = false;
                    $this->form->cantidad_dias_evento = 0;
                } else {
                    // Para tipos 3, 4 y 5
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = true;
                    $this->form->is_cantidad_dias_evento = false;
                    $this->form->cantidad_dias_evento = 0;
                }
            }
        }

        if ($propertyName === 'form.is_independiente' && $this->form->is_independiente) {
            $this->form->is_semana_evento = false;
        }

        if ($propertyName === 'form.is_repetible' && !$this->form->is_repetible) {
            $this->form->is_semana_evento = false;
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

        // Si se activa is_semana_evento, asegurar que existen instancias para ambos lapsos
        if ($propertyName === 'form.is_semana_evento' && $this->form->is_semana_evento) {
            $hasLapso1 = false;
            $hasLapso2 = false;
            foreach ($this->form->semanas as $s) {
                $lapso = is_array($s) ? ($s['lapso'] ?? 1) : 1;
                if ($lapso == 1) $hasLapso1 = true;
                if ($lapso == 2) $hasLapso2 = true;
            }
            if (!$hasLapso1) {
                $this->form->semanas[] = ['lapso' => 1, 'semana' => ''];
            }
            if (!$hasLapso2) {
                $this->form->semanas[] = ['lapso' => 2, 'semana' => ''];
            }
        }

        // Si se desactiva is_semana_evento, limpiar todas las semanas
        if ($propertyName === 'form.is_semana_evento' && !$this->form->is_semana_evento) {
            $this->form->semanas = [];
            $this->resetErrorBag('form.semanas');
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
            $this->form->is_cantidad_dias_evento = false;
        }
        // Limpiar cantidad_dias_evento si el switch se apaga
        if ($propertyName === 'form.is_cantidad_dias_evento' && !$this->form->is_cantidad_dias_evento) {
            $this->form->cantidad_dias_evento = 0;
            $this->resetErrorBag('form.cantidad_dias_evento');
        }

        // 2. FINALMENTE VALIDAMOS EL CAMPO
        $this->form->validateOnly($field);
    }

    public function guardar()
    {
        try {
            if (!$this->form->is_semana_evento) {
                $this->form->semanas = [];
            }
            $this->form->validate();
            $id_repo = $this->eventoRepository->crear($this->form->all());

            $this->reset('form.descripcion_evento', 'form.tipo_evento', 'form.codigo_color_evento', 'form.id_especial_evento', 'form.is_especial', 'form.cantidad_dias_evento');
            $this->form->semanas = [
                ['lapso' => 1, 'semana' => ''],
                ['lapso' => 2, 'semana' => ''],
            ];
            $this->refreshEventos();
            $this->showAlert('success', 'Evento creado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al crear evento: ' . $e->getMessage());
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
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
            ->pluck('id_especial_evento')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.pages.evento.create-evento');
    }
}

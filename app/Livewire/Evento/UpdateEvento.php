<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\UpdateEventoForm;
use Livewire\Component;
use App\Repositories\Evento\EventoUpdateRepo;
use App\Repositories\Evento\EventoViewRepo;
use Exception;

class UpdateEvento extends Component
{
    public UpdateEventoForm $form;
    public $eventosExistentes = [];
    protected $eventoRepository;
    protected $viewRepository;

    public function boot()
    {
        $this->eventoRepository = new EventoUpdateRepo();
        $this->viewRepository = new EventoViewRepo();
    }

    public function mount($id)
    {
        $evento = $this->viewRepository->mostrar($id);
        if (!$evento) {
            return redirect()->route('evento/listar')->with('error', 'Evento no encontrado.');
        }

        $this->form->setEvento($evento);
        $this->refreshEventos();
        if (empty($this->form->semanas)) {
            $this->form->semanas = [''];
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
        if ($propertyName === 'form.especial_evento') {
            if ($this->form->especial_evento == '2' || $this->form->especial_evento == '3') {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->tipo_evento = '4';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->cantidad_dias_evento = 0;
            } elseif (in_array($this->form->especial_evento, ['7', '8'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = true;
                $this->form->tipo_evento = '4';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->cantidad_dias_evento = 0;
            } elseif (in_array($this->form->especial_evento, ['9', '10'])) {
                $this->form->is_laborable = true;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '4';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '1';
                $this->form->is_independiente = true;
                $this->form->cantidad_dias_evento = 0;
            } elseif ($this->form->especial_evento == '1') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = true;
                $this->form->tipo_evento = '5';
                $this->form->is_rango_dias = false;
                $this->form->rango_dias = '';
                $this->form->is_independiente = true;
                $this->form->is_superponible = false;
                $this->form->cantidad_dias_evento = 60;
            } elseif ($this->form->especial_evento == '4') { // Semana Santa
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '2';
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 2;
            } elseif ($this->form->especial_evento == '5') { // Carnaval
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_rango_dias = true;
                $this->form->rango_dias = '2';
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->cantidad_dias_evento = 2;
            } else {
                $this->form->cantidad_dias_evento = 0;
            }
            $nombresEspeciales = [
                '1' => 'Vacaciones Colectivas',
                '2' => 'Inicio del Lapso Académico',
                '3' => 'Fin del Lapso Académico',
                '4' => 'Semana Santa',
                '5' => 'Carnaval',
                '7' => 'Inicio del Lapso Introductorio',
                '8' => 'Fin del Lapso Introductorio',
                '9' => 'Inicio del Curso Intensivo',
                '10' => 'Fin del Curso Intensivo',
            ];

            if (isset($nombresEspeciales[$this->form->especial_evento])) {
                $this->form->descripcion_evento = $nombresEspeciales[$this->form->especial_evento];
            } else {
                $this->form->descripcion_evento = '';
            }
        }

        // Si cambia is_especial
        if ($propertyName === 'form.is_especial' && $this->form->is_especial) {
            $this->form->is_independiente = true;
        }

        // Si cambia el tipo de evento
        if ($propertyName === 'form.tipo_evento') {
            if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                $this->form->is_independiente = true;
                $this->form->is_superponible = true;
                $this->form->is_semana_evento = false;
            } else {
                $this->form->is_independiente = false;
            }

            if (!in_array($this->form->especial_evento, ['1', '2', '3', '4', '5', '7', '8', '9', '10'])) {
                if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = false;
                } else {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = true;
                }
            }
        }

        // Limpiar especial_evento si el switch se apaga
        if ($propertyName === 'form.is_especial' && !$this->form->is_especial) {
            $this->form->especial_evento = '';
            $this->form->cantidad_dias_evento = 0;
            $this->resetErrorBag('form.especial_evento');
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

        // 2. FINALMENTE VALIDAMOS EL CAMPO
        $this->form->validateOnly($field);
    }

    public function guardar()
    {
        try {
            $this->form->validate();
            $this->eventoRepository->actualizar($this->form->id_evento, $this->form->all());
            $this->showAlert('success', 'Evento actualizado correctamente.', '/evento/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
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

    public function agregarSemana()
    {
        if ($this->form->is_repetible) {
            $semanasValidas = array_filter($this->form->semanas ?? [], fn($v) => $v !== null && $v !== '');
            if (count($semanasValidas) >= 4) {
                return;
            }
            $this->form->semanas[] = '';
        }
    }

    public function removerSemana($index)
    {
        unset($this->form->semanas[$index]);
        $this->form->semanas = array_values($this->form->semanas);
    }

    public function render()
    {
        return view('livewire.pages.evento.edit-evento');
    }
}

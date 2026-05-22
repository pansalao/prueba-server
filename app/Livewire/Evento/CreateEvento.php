<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\CreateEventoForm;
use Livewire\Component;
use App\Repositories\Evento\EventoCreateRepo;
use Exception;

class CreateEvento extends Component
{
    public CreateEventoForm $form;
    public $colores = [];
    public $eventosExistentes = [];
    protected $eventoRepository;

    public function boot()
    {
        $this->eventoRepository = new EventoCreateRepo();
    }

    public function mount()
    {
        $this->cargarColores();
        $this->refreshEventos();
    }

    public function refreshEventos()
    {
        $this->eventosExistentes = \App\Models\Evento::orderBy('nombre_evento')->get();
    }

    public function cargarColores()
    {
        $this->colores = $this->eventoRepository->getColoresDisponibles();
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
                $this->form->cantidad_dias_evento = 60;
            } elseif ($this->form->especial_evento == '4') { // Semana Santa
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_rango_dias = false;
                $this->form->rango_dias = '';
                $this->form->is_independiente = true;
                $this->form->cantidad_dias_evento = 0;
            } elseif ($this->form->especial_evento == '5') { // Carnaval
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->tipo_evento = '6';
                $this->form->is_rango_dias = false;
                $this->form->rango_dias = '';
                $this->form->is_independiente = true;
                $this->form->cantidad_dias_evento = 0;
            } else {
                $this->form->cantidad_dias_evento = 0;
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
            } else {
                $this->form->is_independiente = false;
            }

            if (!in_array($this->form->especial_evento, ['1', '2', '3', '4', '5', '7', '8', '9', '10'])) {
                if (in_array($this->form->tipo_evento, ['1', '2', '6'])) {
                    $this->form->is_laborable = false;
                    $this->form->is_repetible = false;
                } else {
                    // Para tipos 3, 4 y 5
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
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
            } else {
                $this->form->is_independiente = false;
                $this->form->is_laborable = false;
                $this->form->is_repetible = true;
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
            $id_repo = $this->eventoRepository->crear($this->form->all());

            $this->reset('form.descripcion_evento', 'form.tipo_evento', 'form.id_color', 'form.especial_evento', 'form.is_especial', 'form.cantidad_dias_evento');
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

    public function render()
    {
        return view('livewire.pages.evento.create-evento');
    }
}
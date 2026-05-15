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
        $this->form->validateOnly($field);

        // Si cambia el tipo de evento
        if ($propertyName === 'form.tipo_evento') {
            if ($this->form->tipo_evento == '1' || $this->form->tipo_evento == '2') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
                $this->form->is_obligatorio = true;
            } else {
                // Para tipos 3, 4 y 5
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
            }
        }
    }

    public function guardar()
    {
        try {
            $this->form->validate();
            $id_repo = $this->eventoRepository->crear($this->form->all());

            $this->reset('form.descripcion_evento', 'form.tipo_evento', 'form.id_color');
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

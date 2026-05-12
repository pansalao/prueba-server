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
    protected $eventoRepository;

    public function boot()
    {
        $this->eventoRepository = new EventoCreateRepo();
    }

    public function mount()
    {
        $this->cargarColores();
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
            if ($this->form->tipo_evento == '1') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
            } else {
                // Para tipos 2 y 3, por defecto desmarcados
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
            }
        }
    }

    public function guardar()
    {
        $this->form->validate();

        try {
            $id_repo = $this->eventoRepository->crear($this->form->all());

            $this->reset('form.descripcion_evento', 'form.tipo_evento', 'form.id_color');
            session()->flash('message', 'Evento creado correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al crear evento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.evento.create-evento');
    }
}

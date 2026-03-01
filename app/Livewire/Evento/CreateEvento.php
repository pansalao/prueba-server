<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\CreateEventoForm;
use Livewire\Component;
use App\Repositories\Evento\EventoCreateRepo;
use Exception;

class CreateEvento extends Component
{
    public CreateEventoForm $form;
    protected $eventoRepository;

    public function __construct()
    {
        $this->eventoRepository = new EventoCreateRepo();
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    public function guardar()
    {
        $this->form->validate();

        try {
            $this->eventoRepository->crear($this->form->all());

            $this->reset('form.descripcion_evento', 'form.id_lapso', 'form.dia_inicio_evento', 'form.dia_fin_evento', 'form.semana_evento', 'form.tipo_evento');
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

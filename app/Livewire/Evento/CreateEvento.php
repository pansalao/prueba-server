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
        $this->colores = \Illuminate\Support\Facades\DB::table('color')->where('estatus', '1')->get();
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

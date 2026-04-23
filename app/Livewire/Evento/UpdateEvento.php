<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\EditEventoForm;
use Livewire\Component;
use App\Repositories\Evento\EventoUpdateRepo;
use App\Repositories\Evento\EventoViewRepo;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateEvento extends Component
{
    public EditEventoForm $form;
    public $colores = [];
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
        $this->colores = DB::table('color')->where('estatus', '1')->get();
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
            $this->eventoRepository->actualizar($this->form->id_evento, $this->form->all());
            session()->flash('message', 'Evento actualizado correctamente.');
            return redirect()->route('evento/listar');
        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar evento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.evento.edit-evento');
    }
}

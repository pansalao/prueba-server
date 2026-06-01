<?php

namespace App\Livewire\Evento;

use Livewire\Component;
use App\Repositories\Evento\EventoViewRepo;
use Livewire\Attributes\Locked;

class ShowEvento extends Component
{
    #[Locked]
    public $eventoId;

    public $evento;
    protected $eventoRepository;

    public function boot(EventoViewRepo $repo)
    {
        $this->eventoRepository = $repo;
    }

    public function mount($id)
    {
        $this->eventoId = $id;
        $this->evento = $this->eventoRepository->mostrar($this->eventoId);

        if (!$this->evento) {
            return redirect()->route('evento/listar')->with('error', 'Evento no encontrado.');
        }
    }

    public function render()
    {
        return view('livewire.pages.evento.show-evento');
    }

    public function cerrar()
    {
        return redirect()->route('evento/listar');
    }
}

<?php

namespace App\Livewire\Evento;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;

class ListEvento extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;



    public function confirmarInhabilitar($id)
    {
        $this->idInhabilitar = $id;
    }

    public function confirmarRestaurar($id)
    {
        $this->idRestaurar = $id;
    }

    public function inhabilitar()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-evento')) {
            abort(403);
        }

        try {
            $repo = new EventoIndexRepo();
            $result = $repo->inhabilitar($this->idInhabilitar);

            if ($result) {
                session()->flash('message', 'Evento inhabilitado exitosamente.');
                $this->dispatch('eventoUpdated');
            } else {
                session()->flash('error', 'No se pudo inhabilitar el evento.');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->idInhabilitar = null;
    }

    public function restaurar()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-evento')) {
            abort(403);
        }

        try {
            $repo = new EventoIndexRepo();
            $result = $repo->restaurar($this->idRestaurar);

            if ($result) {
                session()->flash('message', 'Evento restaurado exitosamente.');
                $this->dispatch('eventoUpdated');
            } else {
                session()->flash('error', 'No se pudo restaurar el evento.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar el evento: ' . $e->getMessage());
        }

        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $repo = new EventoIndexRepo();
        $eventos = $repo->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.evento.list-evento', compact('eventos'));
    }
}

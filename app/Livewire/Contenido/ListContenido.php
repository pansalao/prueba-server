<?php

namespace App\Livewire\Contenido;

use App\Repositories\Contenido\ContenidoIndexRepo;
use Livewire\Component;
use Livewire\WithPagination;

class ListContenido extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;

    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $contenidoRepo;

    public function boot()
    {
        $this->contenidoRepo = new ContenidoIndexRepo();
    }

    public function updatingBusqueda()
    {
        $this->resetPage();
    }

    public function confirmarInhabilitar($id)
    {
        $this->idInhabilitar = $id;
    }

    public function inhabilitar()
    {
        if (!auth()->user()?->esCoordinadorOVicerrector()) {
            abort(403);
        }
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-contenido')) {
            abort(403);
        }
        $this->contenidoRepo->inhabilitar($this->idInhabilitar);
        session()->flash('message', 'Contenido inhabilitado con éxito.');
        $this->idInhabilitar = null;
    }

    public function confirmarRestaurar($id)
    {
        $this->idRestaurar = $id;
    }

    public function restaurar()
    {
        if (!auth()->user()?->esCoordinadorOVicerrector()) {
            abort(403);
        }
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-contenido')) {
            abort(403);
        }
        $this->contenidoRepo->restaurar($this->idRestaurar);
        session()->flash('message', 'Contenido restaurado con éxito.');
        $this->idRestaurar = null;
    }

    public function render()
    {
        return view('livewire.pages.contenido.list-contenido', [
            'contenidos' => $this->contenidoRepo->listar($this->busqueda, $this->paginacion)
        ]);
    }
}

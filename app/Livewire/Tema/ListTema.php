<?php

namespace App\Livewire\Tema;

use App\Repositories\Tema\TemaIndexRepo;
use Livewire\Component;
use Livewire\WithPagination;

class ListTema extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;

    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $temaRepo;

    public function boot()
    {
        $this->temaRepo = new TemaIndexRepo();
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-tema')) {
            abort(403);
        }
        $this->temaRepo->inhabilitar($this->idInhabilitar);
        session()->flash('message', 'Tema inhabilitado con éxito.');
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-tema')) {
            abort(403);
        }
        $this->temaRepo->restaurar($this->idRestaurar);
        session()->flash('message', 'Tema restaurado con éxito.');
        $this->idRestaurar = null;
    }

    public function render()
    {
        return view('livewire.pages.tema.list-tema', [
            'temas' => $this->temaRepo->listar($this->busqueda, $this->paginacion)
        ]);
    }
}

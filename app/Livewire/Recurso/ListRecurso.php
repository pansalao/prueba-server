<?php

namespace App\Livewire\Recurso;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Recurso\RecursoIndexRepo;
use Exception;

class ListRecurso extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $recursosRepository;

    public function __construct()
    {
        $this->recursosRepository = new RecursoIndexRepo();
    }

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
        if (!auth()->user()?->esCoordinadorOVicerrector()) {
            abort(403);
        }
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-recurso')) {
            abort(403);
        }

        try {
            $result = $this->recursosRepository->inhabilitar($this->idInhabilitar);

            if ($result) {
                session()->flash('message', 'Recurso inhabilitado exitosamente.');
            } else {
                session()->flash('error', 'No se pudo inhabilitar el recurso.');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->idInhabilitar = null;
    }

    public function restaurar()
    {
        if (!auth()->user()?->esCoordinadorOVicerrector()) {
            abort(403);
        }
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-recurso')) {
            abort(403);
        }

        try {
            $result = $this->recursosRepository->restaurar($this->idRestaurar);

            if ($result) {
                session()->flash('message', 'Recurso restaurado exitosamente.');
            } else {
                session()->flash('error', 'No se pudo restaurar el recurso.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar el recurso: ' . $e->getMessage());
        }

        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $recursos = $this->recursosRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.recurso.list-recurso', compact('recursos'));
    }
}

<?php

namespace App\Livewire\TecnicaEvaluacion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\TecnicaEvaluacion\TecnicaEvaluacionIndexRepo;
use Exception;

class ListTecnicaEvaluacion extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $evaluacionesRepository;

    public function __construct()
    {
        $this->evaluacionesRepository = new TecnicaEvaluacionIndexRepo();
    }

    public function updatedBusqueda()
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-evaluacion')) {
            abort(403);
        }

        try {
            $this->evaluacionesRepository->inhabilitar($this->idInhabilitar);
            session()->flash('message', 'Técnica de evaluación inhabilitada correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al inhabilitar la técnica de evaluación.');
        }
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-evaluacion')) {
            abort(403);
        }

        try {
            $this->evaluacionesRepository->restaurar($this->idRestaurar);
            session()->flash('message', 'Técnica de evaluación restaurada correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar la técnica de evaluación.');
        }
        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $evaluaciones = $this->evaluacionesRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.tecnica-evaluacion.list-tecnica-evaluacion', compact('evaluaciones'));
    }
}

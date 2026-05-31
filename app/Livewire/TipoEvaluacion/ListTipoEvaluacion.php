<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\TipoEvaluacion\TipoEvaluacionIndexRepo;
use Exception;

class ListTipoEvaluacion extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $tipoEvaluacionRepository;

    public function __construct()
    {
        $this->tipoEvaluacionRepository = new TipoEvaluacionIndexRepo();
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-tipo-evaluacion')) {
            abort(403);
        }

        try {
            $this->tipoEvaluacionRepository->inhabilitar($this->idInhabilitar);
            session()->flash('message', 'Tipo de evaluación inhabilitado correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al inhabilitar el tipo de evaluación.');
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-tipo-evaluacion')) {
            abort(403);
        }

        try {
            $this->tipoEvaluacionRepository->restaurar($this->idRestaurar);
            session()->flash('message', 'Tipo de evaluación restaurado correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar el tipo de evaluación.');
        }
        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $tiposEvaluacion = $this->tipoEvaluacionRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.tipo-evaluacion.list-tipo-evaluacion', compact('tiposEvaluacion'));
    }
}

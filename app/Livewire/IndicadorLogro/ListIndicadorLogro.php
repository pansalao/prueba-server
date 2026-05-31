<?php

namespace App\Livewire\IndicadorLogro;

use App\Repositories\IndicadorLogro\IndicadorLogroIndexRepo;
use Livewire\Component;
use Livewire\WithPagination;

class ListIndicadorLogro extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $indicadorRepo;

    public function __construct()
    {
        $this->indicadorRepo = new IndicadorLogroIndexRepo();
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-indicador-logro')) {
            abort(403);
        }

        try {
            $result = $this->indicadorRepo->inhabilitar($this->idInhabilitar);
            if ($result) {
                session()->flash('message', 'Indicador inhabilitado correctamente.');
            } else {
                session()->flash('error', 'No se pudo inhabilitar el indicador.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al inhabilitar: ' . $e->getMessage());
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-indicador-logro')) {
            abort(403);
        }

        try {
            $result = $this->indicadorRepo->restaurar($this->idRestaurar);
            if ($result) {
                session()->flash('message', 'Indicador restaurado correctamente.');
            } else {
                session()->flash('error', 'No se pudo restaurar el indicador.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al restaurar: ' . $e->getMessage());
        }
        $this->idRestaurar = null;
    }

    public function render()
    {
        return view('livewire.pages.indicador-logro.list-indicador-logro', [
            'indicadores' => $this->indicadorRepo->obtener_indicadores($this->busqueda, $this->paginacion)
        ]);
    }
}

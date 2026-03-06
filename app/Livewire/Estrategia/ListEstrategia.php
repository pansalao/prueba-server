<?php

namespace App\Livewire\Estrategia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Estrategia\EstrategiaIndexRepo;
use Exception;

class ListEstrategia extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $estrategiasRepository;

    public function __construct()
    {
        $this->estrategiasRepository = new EstrategiaIndexRepo();
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-estrategia')) {
            abort(403);
        }

        try {
            $this->estrategiasRepository->inhabilitar($this->idInhabilitar);
            session()->flash('message', 'Estrategia inhabilitada correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al inhabilitar la estrategia.');
        }
        $this->idInhabilitar = null;
    }

    public function confirmarRestaurar($id)
    {
        $this->idRestaurar = $id;
    }

    public function restaurar()
    {
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-estrategia')) {
            abort(403);
        }

        try {
            $this->estrategiasRepository->restaurar($this->idRestaurar);
            session()->flash('message', 'Estrategia restaurada correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar la estrategia.');
        }
        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $estrategias = $this->estrategiasRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.estrategia.list-estrategia', compact('estrategias'));
    }
}

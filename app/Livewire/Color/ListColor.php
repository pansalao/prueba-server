<?php

namespace App\Livewire\Color;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Color\ColorIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;

class ListColor extends Component
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
        if (!Gate::allows('cambiar-estatus-color')) {
            abort(403);
        }

        try {
            $repo = new ColorIndexRepo();
            $result = $repo->inhabilitar($this->idInhabilitar);

            if ($result) {
                session()->flash('message', 'Color inhabilitado exitosamente.');
                $this->dispatch('colorUpdated');
            } else {
                session()->flash('error', 'No se pudo inhabilitar el color.');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->idInhabilitar = null;
    }

    public function restaurar()
    {
        if (!Gate::allows('cambiar-estatus-color')) {
            abort(403);
        }

        try {
            $repo = new ColorIndexRepo();
            $result = $repo->restaurar($this->idRestaurar);

            if ($result) {
                session()->flash('message', 'Color restaurado exitosamente.');
                $this->dispatch('colorUpdated');
            } else {
                session()->flash('error', 'No se pudo restaurar el color.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar el color: ' . $e->getMessage());
        }

        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $repo = new ColorIndexRepo();
        $colores = $repo->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.color.list-color', compact('colores'));
    }
}

<?php

namespace App\Livewire\Bibliografia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Bibliografia\BibliografiaIndexRepo;
use Exception;

class ListBibliografia extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    public $idInhabilitar = null;
    public $idRestaurar = null;

    protected $bibliografiasRepository;

    public function __construct()
    {
        $this->bibliografiasRepository = new BibliografiaIndexRepo();
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-bibliografia')) {
            abort(403);
        }

        try {
            $result = $this->bibliografiasRepository->inhabilitar($this->idInhabilitar);

            if ($result) {
                session()->flash('message', 'Bibliografía inhabilitada exitosamente.');
                $this->dispatch('bibliografia-updated');
            } else {
                session()->flash('error', 'No se pudo inhabilitar la bibliografía.');
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
        if (!\Illuminate\Support\Facades\Gate::allows('cambiar-estatus-bibliografia')) {
            abort(403);
        }

        try {
            $result = $this->bibliografiasRepository->restaurar($this->idRestaurar);

            if ($result) {
                session()->flash('message', 'Bibliografía restaurada exitosamente.');
                $this->dispatch('bibliografia-updated');
            } else {
                session()->flash('error', 'No se pudo restaurar la bibliografía.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al restaurar la bibliografía: ' . $e->getMessage());
        }

        $this->idRestaurar = null;
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $bibliografias = $this->bibliografiasRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.bibliografia.list-bibliografia', compact('bibliografias'));
    }
}

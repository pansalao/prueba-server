<?php

namespace App\Livewire\Contenido;

use App\Livewire\Forms\Contenido\UpdateContenidoForm;
use App\Repositories\Contenido\ContenidoCreateRepo;
use App\Repositories\Contenido\ContenidoEditRepo;
use Livewire\Component;

class UpdateContenido extends Component
{
    public UpdateContenidoForm $form;
    public $temas = [];

    protected $contenidoRepo;
    protected $contenidoEditRepo;

    public function boot()
    {
        $this->contenidoRepo = new ContenidoCreateRepo();
        $this->contenidoEditRepo = new ContenidoEditRepo();
    }

    public function mount($id)
    {
        $contenido = $this->contenidoEditRepo->mostrar($id);
        if (!$contenido) {
            return redirect()->route('contenido/listar');
        }

        $this->form->setContenido($contenido);
        $this->temas = $this->contenidoRepo->select_temas();
    }

    public function save()
    {
        $this->form->validate();
        $this->contenidoEditRepo->editar($this->form->id, $this->form->values());
        session()->flash('message', 'Contenido actualizado con éxito.');
        return redirect()->route('contenido/listar');
    }

    public function render()
    {
        return view('livewire.pages.contenido.update-contenido');
    }
}

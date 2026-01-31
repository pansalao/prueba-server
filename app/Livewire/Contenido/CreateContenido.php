<?php

namespace App\Livewire\Contenido;

use App\Livewire\Forms\Contenido\CreateContenidoForm;
use App\Repositories\Contenido\ContenidoCreateRepo;
use Livewire\Component;

class CreateContenido extends Component
{
    public CreateContenidoForm $form;
    public $temas = [];

    protected $contenidoRepo;

    public function boot()
    {
        $this->contenidoRepo = new ContenidoCreateRepo();
    }

    public function mount()
    {
        $this->temas = $this->contenidoRepo->select_temas();
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    public function save()
    {
        $this->form->validate();
        try {
            $this->contenidoRepo->crear($this->form->values());
            $this->form->reset(); // Resets public properties in the form object
            session()->flash('message', 'Contenido creado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error inténtelo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.pages.contenido.create-contenido');
    }
}

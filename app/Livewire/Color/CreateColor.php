<?php

namespace App\Livewire\Color;

use App\Livewire\Forms\Color\CreateColorForm;
use Livewire\Component;
use App\Repositories\Color\ColorCreateRepo;
use Exception;
use Illuminate\Support\Facades\Gate;

class CreateColor extends Component
{
    public CreateColorForm $form;
    protected $colorRepository;

    public function boot()
    {
        $this->colorRepository = new ColorCreateRepo();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function guardar()
    {
        if (!Gate::allows('crear-color')) {
            abort(403);
        }

        $this->validate();

        try {
            $this->colorRepository->crear($this->form->all());

            session()->flash('message', 'Color creado exitosamente.');
            return redirect()->route('color.list');
        } catch (Exception $e) {
            session()->flash('error', 'Error al crear el color: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.color.create-color');
    }
}

<?php

namespace App\Livewire\Color;

use App\Livewire\Forms\Color\UpdateColorForm;
use Livewire\Component;
use App\Repositories\Color\ColorUpdateRepo;
use Exception;
use Illuminate\Support\Facades\Gate;

class UpdateColor extends Component
{
    public UpdateColorForm $form;
    public $id_color;
    protected $colorRepository;

    public function boot()
    {
        $this->colorRepository = new ColorUpdateRepo();
    }

    public function mount($id)
    {
        if (!Gate::allows('editar-color')) {
            abort(403);
        }

        $this->id_color = $id;
        $color = $this->colorRepository->obtenerPorId($id);

        if (!$color) {
            session()->flash('error', 'Color no encontrado.');
            return redirect()->route('color.list');
        }

        $this->form->setColor($color);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function actualizar()
    {
        if (!Gate::allows('editar-color')) {
            abort(403);
        }

        $this->validate();

        try {
            $this->colorRepository->actualizar($this->id_color, $this->form->all());

            session()->flash('message', 'Color actualizado exitosamente.');
            return redirect()->route('color.list');
        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el color: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.color.edit-color');
    }
}

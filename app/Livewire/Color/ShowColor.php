<?php

namespace App\Livewire\Color;

use Livewire\Component;
use App\Repositories\Color\ColorViewRepo;
use Illuminate\Support\Facades\Gate;

class ShowColor extends Component
{
    public $color;

    public function mount($id)
    {
        if (!Gate::allows('ver-color')) {
            abort(403);
        }

        $repo = new ColorViewRepo();
        $this->color = $repo->mostrar($id);

        if (!$this->color) {
            session()->flash('error', 'Color no encontrado.');
            return redirect()->route('color.list');
        }
    }

    public function render()
    {
        return view('livewire.pages.color.show-color');
    }
}

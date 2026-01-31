<?php

namespace App\Livewire\Tema;

use App\Livewire\Forms\Tema\CreateTemaForm;
use App\Repositories\Tema\TemaCreateRepo;
use Livewire\Component;

class CreateTema extends Component
{
    public CreateTemaForm $form;
    public $unidadesCurriculares = [];
    public $cortes = [];

    protected $temaRepo;

    public function boot()
    {
        $this->temaRepo = new TemaCreateRepo();
    }

    public function mount()
    {
        $this->unidadesCurriculares = $this->temaRepo->select_unidades_curriculares();
        $this->cortes = [
            (object) ['id' => '1', 'nombre' => 'Corte 1'],
            (object) ['id' => '2', 'nombre' => 'Corte 2'],
            (object) ['id' => '3', 'nombre' => 'Corte 3'],
            (object) ['id' => '4', 'nombre' => 'Corte 4'],
        ];
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
            $this->temaRepo->crear($this->form->values());
            $this->form->reset();
            session()->flash('message', 'Tema creado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.tema.create-tema');
    }
}

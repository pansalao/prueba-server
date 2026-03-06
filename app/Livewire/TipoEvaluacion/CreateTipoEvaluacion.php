<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TipoEvaluacion\CreateTipoEvaluacionForm;
use App\Repositories\TipoEvaluacion\TipoEvaluacionCreateRepo;
use Exception;

class CreateTipoEvaluacion extends Component
{
    public CreateTipoEvaluacionForm $form;
    protected $tipoEvaluacionRepository;

    public function __construct()
    {
        $this->tipoEvaluacionRepository = new TipoEvaluacionCreateRepo();
    }

    public function guardar()
    {
        $this->form->validate();

        try {
            $this->tipoEvaluacionRepository->crear($this->form->all());
            $this->reset('form.nombre');
            session()->flash('message', 'Tipo de evaluación creado correctamente.');
        } catch (Exception $e) {
            session()->flash('error', 'Error al crear el tipo de evaluación. Inténtelo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.pages.tipo-evaluacion.create-tipo-evaluacion');
    }
}

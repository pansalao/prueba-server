<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TipoEvaluacion\UpdateTipoEvaluacionForm;
use App\Repositories\TipoEvaluacion\TipoEvaluacionEditRepo;
use Exception;

class UpdateTipoEvaluacion extends Component
{
    public UpdateTipoEvaluacionForm $form;
    protected $tipoEvaluacionRepository;

    public function __construct()
    {
        $this->tipoEvaluacionRepository = new TipoEvaluacionEditRepo();
    }

    public function mount($id)
    {
        try {
            $tipoEvaluacion = $this->tipoEvaluacionRepository->obtenerPorId($id);
            if (!$tipoEvaluacion) {
                return redirect()->route('tipo-evaluacion/listar')->with('error', 'Tipo de evaluación no encontrado.');
            }
            $this->form->setForm($tipoEvaluacion);
        } catch (Exception $e) {
            return redirect()->route('tipo-evaluacion/listar')->with('error', 'Error al cargar el tipo de evaluación.');
        }
    }

    public function actualizar()
    {
        $this->form->validate();

        try {
            $this->tipoEvaluacionRepository->actualizar($this->form->id_tipo_evaluacion, $this->form->all());
            session()->flash('message', 'Tipo de evaluación actualizado correctamente.');
            return redirect()->route('tipo-evaluacion/listar');
        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el tipo de evaluación.');
        }
    }

    public function cancelar()
    {
        return redirect()->route('tipo-evaluacion/listar');
    }

    public function render()
    {
        return view('livewire.pages.tipo-evaluacion.update-tipo-evaluacion');
    }
}

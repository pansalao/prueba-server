<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use App\Repositories\TipoEvaluacion\TipoEvaluacionViewRepo;
use Exception;

class ShowTipoEvaluacion extends Component
{
    public $tipoEvaluacion;
    protected $tipoEvaluacionRepository;

    public function __construct()
    {
        $this->tipoEvaluacionRepository = new TipoEvaluacionViewRepo();
    }

    public function mount(int $id)
    {
        try {
            $this->tipoEvaluacion = $this->tipoEvaluacionRepository->mostrar($id);
            if (!$this->tipoEvaluacion) {
                return redirect()->route('tipo-evaluacion/listar')->with('error', 'Tipo de evaluación no encontrado.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Error al cargar el tipo de evaluación: ' . $e->getMessage());
            return redirect()->route('tipo-evaluacion/listar');
        }
    }

    public function cerrar()
    {
        return redirect()->route('tipo-evaluacion/listar');
    }

    public function render()
    {
        return view('livewire.pages.tipo-evaluacion.show-tipo-evaluacion');
    }
}

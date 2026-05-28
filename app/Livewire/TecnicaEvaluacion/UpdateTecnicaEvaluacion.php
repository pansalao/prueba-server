<?php

namespace App\Livewire\TecnicaEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TecnicaEvaluacion\UpdateTecnicaEvaluacionForm;
use App\Repositories\TecnicaEvaluacion\TecnicaEvaluacionEditRepo;
use Exception;

class UpdateTecnicaEvaluacion extends Component
{
    public UpdateTecnicaEvaluacionForm $form;
    public $tecnicasExistentes;
    protected $evaluacionesRepository;

    public function __construct()
    {
        $this->evaluacionesRepository = new TecnicaEvaluacionEditRepo();
    }

    public function mount($id)
    {
        try {
            $evaluacion = $this->evaluacionesRepository->obtenerPorId($id);
            if (!$evaluacion) {
                return redirect()->route('tecnica-evaluacion/listar')->with('error', 'Técnica de evaluación no encontrada.');
            }
            $this->form->setForm($evaluacion);
            $this->refreshTecnicas();
        } catch (Exception $e) {
            return redirect()->route('tecnica-evaluacion/listar')->with('error', 'Error al cargar la técnica de evaluación.');
        }
    }

    public function refreshTecnicas()
    {
        $this->tecnicasExistentes = \App\Models\TecnicaEvaluacion::where('estatus', '1')
            ->orderBy('nombre_tecnica_evaluacion')
            ->get();
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    public function actualizar()
    {
        try {
            $this->form->validate();
            $this->evaluacionesRepository->actualizar($this->form->id_tecnica_evaluacion, $this->form->all());
            $this->showAlert('success', 'Técnica de evaluación actualizada correctamente.', '/tecnica-evaluacion/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar la técnica de evaluación.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function cancelar()
    {
        return redirect()->route('tecnica-evaluacion/listar');
    }

    public function render()
    {
        return view('livewire.pages.tecnica-evaluacion.update-tecnica-evaluacion');
    }
}

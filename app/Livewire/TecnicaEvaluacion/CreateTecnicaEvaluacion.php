<?php

namespace App\Livewire\TecnicaEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TecnicaEvaluacion\CreateTecnicaEvaluacionForm;
use App\Repositories\TecnicaEvaluacion\TecnicaEvaluacionCreateRepo;
use Exception;

class CreateTecnicaEvaluacion extends Component
{
    public CreateTecnicaEvaluacionForm $form;
    public $tecnicasExistentes;
    protected $evaluacionesRepository;

    public function __construct()
    {
        $this->evaluacionesRepository = new TecnicaEvaluacionCreateRepo();
    }

    public function mount()
    {
        $this->refreshTecnicas();
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

    public function guardar()
    {
        try {
            $this->form->validate();
            $this->evaluacionesRepository->crear($this->form->all());
            $this->reset('form.nombre');
            $this->refreshTecnicas();
            $this->showAlert('success', 'Técnica de evaluación creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al crear la técnica de evaluación. Inténtelo de nuevo.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function render()
    {
        return view('livewire.pages.tecnica-evaluacion.create-tecnica-evaluacion');
    }
}

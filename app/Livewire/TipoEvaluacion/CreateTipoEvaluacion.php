<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TipoEvaluacion\CreateTipoEvaluacionForm;
use App\Repositories\TipoEvaluacion\TipoEvaluacionCreateRepo;
use Exception;

class CreateTipoEvaluacion extends Component
{
    public CreateTipoEvaluacionForm $form;
    public $tiposExistentes;
    protected $tipoEvaluacionRepository;

    public function __construct()
    {
        $this->tipoEvaluacionRepository = new TipoEvaluacionCreateRepo();
    }

    public function mount()
    {
        $this->refreshTipos();
    }

    public function refreshTipos()
    {
        $this->tiposExistentes = \App\Models\TipoEvaluacion::where('estatus', '1')
            ->orderBy('nombre_tipo_evaluacion')
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
            $this->tipoEvaluacionRepository->crear($this->form->all());
            $this->reset('form.nombre');
            $this->refreshTipos();
            $this->showAlert('success', 'Tipo de evaluación creado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al crear el tipo de evaluación. Inténtelo de nuevo.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function render()
    {
        return view('livewire.pages.tipo-evaluacion.create-tipo-evaluacion');
    }
}

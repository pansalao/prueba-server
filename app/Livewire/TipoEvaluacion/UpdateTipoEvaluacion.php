<?php

namespace App\Livewire\TipoEvaluacion;

use Livewire\Component;
use App\Livewire\Forms\TipoEvaluacion\UpdateTipoEvaluacionForm;
use App\Repositories\TipoEvaluacion\TipoEvaluacionEditRepo;
use Exception;

class UpdateTipoEvaluacion extends Component
{
    public UpdateTipoEvaluacionForm $form;
    public $tiposExistentes;
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
            $this->refreshTipos();
        } catch (Exception $e) {
            return redirect()->route('tipo-evaluacion/listar')->with('error', 'Error al cargar el tipo de evaluación.');
        }
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

    public function actualizar()
    {
        try {
            $this->form->validate();
            $this->tipoEvaluacionRepository->actualizar($this->form->id_tipo_evaluacion, $this->form->all());
            $this->showAlert('success', 'Tipo de evaluación actualizado correctamente.', '/tipo-evaluacion/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar el tipo de evaluación.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
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

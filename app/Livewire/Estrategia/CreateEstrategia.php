<?php

namespace App\Livewire\Estrategia;

use Livewire\Component;
use App\Livewire\Forms\Estrategia\CreateEstrategiaForm;
use App\Repositories\Estrategia\EstrategiaCreateRepo;
use Exception;

class CreateEstrategia extends Component
{
    public CreateEstrategiaForm $form;
    public $estrategiasExistentes;
    protected $estrategiasRepository;

    public function __construct()
    {
        $this->estrategiasRepository = new EstrategiaCreateRepo();
    }

    public function mount()
    {
        $this->refreshEstrategias();
    }

    public function refreshEstrategias()
    {
        $this->estrategiasExistentes = \App\Models\Estrategia::where('estatus', '1')
            ->orderBy('nombre_tecnica_actividad')
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
            $this->estrategiasRepository->crear($this->form->all());
            $this->reset('form.nombre');
            $this->refreshEstrategias();
            $this->showAlert('success', 'Estrategia pedagógica creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al crear la estrategia pedagógica. Inténtelo de nuevo.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function render()
    {
        return view('livewire.pages.estrategia.create-estrategia');
    }
}

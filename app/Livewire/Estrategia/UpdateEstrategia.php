<?php

namespace App\Livewire\Estrategia;

use Livewire\Component;
use App\Livewire\Forms\Estrategia\UpdateEstrategiaForm;
use App\Repositories\Estrategia\EstrategiaEditRepo;
use Exception;

class UpdateEstrategia extends Component
{
    public UpdateEstrategiaForm $form;
    public $estrategiasExistentes;
    protected $estrategiasRepository;

    public function __construct()
    {
        $this->estrategiasRepository = new EstrategiaEditRepo();
    }

    public function mount($id)
    {
        try {
            $estrategia = $this->estrategiasRepository->obtenerPorId($id);
            if (!$estrategia) {
                return redirect()->route('estrategia/listar')->with('error', 'Estrategia no encontrada.');
            }
            $this->form->setForm($estrategia);
            $this->refreshEstrategias();
        } catch (Exception $e) {
            return redirect()->route('estrategia/listar')->with('error', 'Error al cargar la estrategia.');
        }
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

    public function actualizar()
    {
        try {
            $this->form->validate();
            $this->estrategiasRepository->actualizar($this->form->id_estrategia_pedagogica, $this->form->all());
            $this->showAlert('success', 'Estrategia actualizada correctamente.', '/estrategia/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar la estrategia.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function cancelar()
    {
        return redirect()->route('estrategia/listar');
    }

    public function render()
    {
        return view('livewire.pages.estrategia.update-estrategia');
    }
}

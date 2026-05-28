<?php

namespace App\Livewire\Tema;

use App\Livewire\Forms\Tema\UpdateTemaForm;
use App\Repositories\Tema\TemaCreateRepo;
use App\Repositories\Tema\TemaEditRepo;
use Livewire\Component;

class UpdateTema extends Component
{
    public UpdateTemaForm $form;
    public $unidadesCurriculares = [];
    public $cortes = [];
    public $temasExistentes = [];
    public $objetivosExistentes = [];

    protected $temaRepo;
    protected $temaEditRepo;

    public function boot()
    {
        $this->temaRepo = new TemaCreateRepo();
        $this->temaEditRepo = new TemaEditRepo();
    }

    public function mount($id)
    {
        $tema = $this->temaEditRepo->mostrar($id);
        if (!$tema) {
            return redirect()->route('tema/listar');
        }

        $this->form->setTema($tema, $tema->objetivos ?? []);
        $this->unidadesCurriculares = $this->temaRepo->select_unidades_curriculares();
        $this->cortes = [
            (object) ['id' => '1', 'nombre' => 'Corte 1'],
            (object) ['id' => '2', 'nombre' => 'Corte 2'],
            (object) ['id' => '3', 'nombre' => 'Corte 3'],
            (object) ['id' => '4', 'nombre' => 'Corte 4'],
        ];
        $this->refreshExistentes();
    }

    public function refreshExistentes()
    {
        $this->temasExistentes = \App\Models\Tema::where('estatus', '1')
            ->orderBy('titulo_tema')
            ->get();
        
        $this->objetivosExistentes = \App\Models\Objetivo::where('estatus', '1')
            ->orderBy('titulo_objetivo')
            ->get();
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    public function addObjetivo()
    {
        $this->form->objetivos[] = ['titulo_objetivo' => ''];
    }

    public function removeObjetivo($index)
    {
        if (count($this->form->objetivos) > 1) {
            unset($this->form->objetivos[$index]);
            $this->form->objetivos = array_values($this->form->objetivos);
        }
    }

    public function save()
    {
        try {
            $this->form->validate();
            $this->temaEditRepo->editar($this->form->id, $this->form->values());
            $this->showAlert('success', 'Tema actualizado con éxito.', '/tema/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (\Exception $e) {
            $this->showAlert('error', 'Error inténtelo de nuevo.');
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function render()
    {
        return view('livewire.pages.tema.update-tema');
    }
}

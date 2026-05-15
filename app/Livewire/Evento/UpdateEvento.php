<?php

namespace App\Livewire\Evento;

use App\Livewire\Forms\Evento\UpdateEventoForm;
use Livewire\Component;
use App\Repositories\Evento\EventoUpdateRepo;
use App\Repositories\Evento\EventoViewRepo;
use Exception;
use Illuminate\Support\Facades\DB;

class UpdateEvento extends Component
{
    public UpdateEventoForm $form;
    public $colores = [];
    public $eventosExistentes = [];
    protected $eventoRepository;
    protected $viewRepository;

    public function boot()
    {
        $this->eventoRepository = new EventoUpdateRepo();
        $this->viewRepository = new EventoViewRepo();
    }

    public function mount($id)
    {
        $evento = $this->viewRepository->mostrar($id);
        if (!$evento) {
            return redirect()->route('evento/listar')->with('error', 'Evento no encontrado.');
        }

        $this->form->setEvento($evento);
        $this->cargarColores();
        $this->refreshEventos();
    }

    public function refreshEventos()
    {
        $this->eventosExistentes = \App\Models\Evento::orderBy('nombre_evento')->get();
    }

    public function cargarColores()
    {
        $this->colores = $this->eventoRepository->getColoresDisponibles($this->form->id_evento);
    }

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);

        // Si cambia el tipo de evento
        if ($propertyName === 'form.tipo_evento') {
            if ($this->form->tipo_evento == '1' || $this->form->tipo_evento == '2') {
                $this->form->is_laborable = false;
                $this->form->is_repetible = false;
            }
        }
    }

    public function guardar()
    {
        try {
            $this->form->validate();
            $this->eventoRepository->actualizar($this->form->id_evento, $this->form->all());
            $this->showAlert('success', 'Evento actualizado correctamente.', '/evento/list');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $msg = "Hay errores en el formulario:\n\n• " . implode("\n• ", $errors);
            $this->showAlert('error', $msg);
            throw $e;
        } catch (Exception $e) {
            $this->showAlert('error', 'Error al actualizar evento: ' . $e->getMessage());
        }
    }

    protected function showAlert($type, $message, $redirect = null)
    {
        $data = json_encode(['type' => $type, 'message' => $message, 'redirect' => $redirect]);
        $this->js("window.dispatchEvent(new CustomEvent('show-alert', { detail: {$data} }))");
    }

    public function cancelar()
    {
        return redirect()->route('evento/listar');
    }

    public function render()
    {
        return view('livewire.pages.evento.edit-evento');
    }
}

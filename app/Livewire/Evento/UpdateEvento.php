<?php

namespace App\Livewire\Evento;

use Livewire\Component;
use Livewire\Attributes\Locked;
use App\Livewire\Forms\Evento\UpdateEventoForm;
use App\Repositories\Evento\EventoEditRepo;
use Exception;

class UpdateEvento extends Component
{
    public UpdateEventoForm $form;

    #[Locked]
    public $eventoId;

    protected $eventoRepository;

    public function __construct()
    {
        $this->eventoRepository = new EventoEditRepo();
    }

    public function mount($id)
    {
        try {
            $this->eventoId = $id;
            $evento = $this->eventoRepository->mostrar($this->eventoId);

            if (!$evento) {
                return redirect()->route('evento/listar')->with('error', 'Evento no encontrado.');
            }

            // Cambio aquí: Se extraen los atributos del modelo correctamente
            $this->form->fill($evento->toArray());

        } catch (Exception $e) {
            session()->flash('error', 'Error al cargar el evento: ' . $e->getMessage());
            return redirect()->route('evento/listar');
        }
    }
    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        $this->form->validateOnly($field);
    }

    public function actualizar()
    {
        $this->form->validate();

        try {
            $this->eventoRepository->editar($this->eventoId, $this->form->all());

            return redirect()->route('evento/listar')
                ->with('message', 'Evento actualizado correctamente.');

        } catch (Exception $e) {
            return redirect()->route('evento/listar')
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        return redirect()->route('evento/listar');
    }

    public function render()
    {
        return view('livewire.pages.evento.update-evento');
    }
}

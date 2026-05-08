<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Livewire\Forms\Calendario\CreateCalendarioForm;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;

class CreateCalendario extends Component
{
    public CreateCalendarioForm $form;
    protected $calendarioRepository;

    public $eventosRegistrados = [];
    public $bibliotecaEventos = [];
    public $paso = 1; // Wizard step
    public $currentYear;

    public function boot()
    {
        $this->calendarioRepository = new CalendarioCreateRepo();
    }

    public function mount()
    {
        $this->currentYear = date('Y');
        
        // Cargar la biblioteca de eventos (templates)
        $eventoRepo = new EventoIndexRepo();
        $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
    }

    public function agregarEvento($inicio, $fin, $id_evento, $nombre, $tipo, $color)
    {
        // Validar el nombre y tipo usando las reglas definidas en el form
        $this->form->validarEvento($nombre, $tipo);

        if (empty($id_evento) || empty($nombre) || empty($tipo)) {
            return;
        }

        // Validación para evitar seleccionar un evento dos veces
        foreach ($this->eventosRegistrados as $evento) {
            if (isset($evento['id']) && $evento['id'] == $id_evento) {
                return; // Ya está registrado
            }
        }

        $this->eventosRegistrados[] = [
            'id' => $id_evento,
            'inicio' => $inicio,
            'fin' => $fin,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'color' => $color,
        ];
    }

    public function removerEvento($index)
    {
        if (isset($this->eventosRegistrados[$index])) {
            unset($this->eventosRegistrados[$index]);
            $this->eventosRegistrados = array_values($this->eventosRegistrados);
        }
    }

    public function avanzarPaso2()
    {
        $this->form->validate();
        $this->paso = 2;
    }

    public function retrocederPaso1()
    {
        $this->paso = 1;
        $this->eventosRegistrados = []; // Opcional: limpiar eventos al volver
    }

    public function save()
    {
        if (!Gate::allows('crear-calendario')) {
            abort(403);
        }

        if (count($this->eventosRegistrados) === 0) {
            session()->flash('error', 'Debe registrar al menos un evento antes de guardar el calendario.');
            return;
        }

        try {
            $this->form->validate();
            
            $id = $this->calendarioRepository->crearConEventos([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
            ], $this->eventosRegistrados);

            if ($id) {
                session()->flash('message', 'Calendario guardado exitosamente.');
                return redirect()->route('calendario.list');
            } else {
                session()->flash('error', 'No se pudo guardar el calendario.');
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.calendario.create-calendario');
    }
}

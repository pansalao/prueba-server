<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Livewire\Forms\Calendario\CreateCalendarioForm;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Repositories\Calendario\CalendarioViewRepo;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class EditarCalendario extends Component
{
    public CreateCalendarioForm $form;
    protected $calendarioRepository;
    protected $viewRepository;

    public $eventosRegistrados = [];
    public $bibliotecaEventos = [];
    public $currentYear;
    public $id_calendario;

    public function boot()
    {
        $this->calendarioRepository = new CalendarioCreateRepo();
        $this->viewRepository = new CalendarioViewRepo();
    }

    public function mount($id)
    {
        if (!Gate::allows('ver-calendario')) {
            abort(403);
        }

        $this->id_calendario = $id;
        $calendario = $this->viewRepository->mostrar($id);

        if (!$calendario || $calendario->estatus != 2) {
            return redirect()->route('calendario.list')->with('error', 'El calendario no está en revisión o no existe.');
        }

        // Cargar datos en el formulario
        $this->form->dia_inicio_calendario_academico = $calendario->dia_inicio_calendario_academico;
        $this->form->dia_fin_calendario_academico = $calendario->dia_fin_calendario_academico;
        
        $this->currentYear = date('Y', strtotime($calendario->dia_inicio_calendario_academico));

        // Cargar eventos registrados
        $eventos = DB::table('detalle_evento')
            ->join('evento', 'detalle_evento.id_evento', '=', 'evento.id_evento')
            ->leftJoin('color', 'evento.id_color', '=', 'color.id_color')
            ->where('detalle_evento.id_calendario_academico', $id)
            ->select(
                'evento.id_evento as id',
                'detalle_evento.dia_inicio_detalle_evento as inicio',
                'detalle_evento.dia_fin_detalle_evento as fin',
                'evento.nombre_evento as nombre',
                'evento.tipo_evento as tipo',
                'color.codigo_color as color'
            )
            ->get();

        foreach ($eventos as $ev) {
            $this->eventosRegistrados[] = [
                'id' => $ev->id,
                'inicio' => $ev->inicio,
                'fin' => $ev->fin,
                'nombre' => $ev->nombre,
                'tipo' => $ev->tipo,
                'color' => $ev->color,
            ];
        }

        // Cargar la biblioteca de eventos (templates)
        $eventoRepo = new EventoIndexRepo();
        $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName == 'form.dia_inicio_calendario_academico' || $propertyName == 'form.dia_fin_calendario_academico') {
            $this->filtrarEventosFueraDeRango();
        }
    }

    protected function filtrarEventosFueraDeRango()
    {
        $inicio = $this->form->dia_inicio_calendario_academico;
        $fin = $this->form->dia_fin_calendario_academico;

        if (!$inicio || !$fin) return;

        $this->eventosRegistrados = array_filter($this->eventosRegistrados, function($evento) use ($inicio, $fin) {
            return ($evento['inicio'] >= $inicio && $evento['inicio'] <= $fin) &&
                   ($evento['fin'] >= $inicio && $evento['fin'] <= $fin);
        });

        $this->eventosRegistrados = array_values($this->eventosRegistrados);
    }

    public function agregarEvento($inicio, $fin, $id_evento, $nombre, $tipo, $color)
    {
        $this->form->validarEvento($nombre, $tipo);

        if (empty($id_evento) || empty($nombre) || empty($tipo)) {
            return;
        }

        foreach ($this->eventosRegistrados as $evento) {
            if (isset($evento['id']) && $evento['id'] == $id_evento) {
                $eventoInfo = collect($this->bibliotecaEventos)->firstWhere('id_evento', $id_evento);
                if (!$eventoInfo || !$eventoInfo->is_repetible_evento) {
                    return; 
                }
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

    public function validarSeccionFechas()
    {
        $this->form->validate();
        $this->dispatch('seccion-fechas-validada');
    }

    public function aprobar()
    {
        if (!Gate::allows('cambiar-estatus-calendario')) {
            abort(403);
        }

        try {
            DB::transaction(function () {
                // 1. Actualizar estatus del calendario y posibles cambios de fechas
                DB::table('calendario_academico')
                    ->where('id_calendario_academico', $this->id_calendario)
                    ->update([
                        'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                        'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                        'estatus' => '1'
                    ]);

                // 2. Limpiar eventos previos y guardar los actuales (por si hubo cambios en la revisión)
                DB::table('detalle_evento')
                    ->where('id_calendario_academico', $this->id_calendario)
                    ->delete();

                foreach ($this->eventosRegistrados as $evento) {
                    DB::table('detalle_evento')->insert([
                        'id_calendario_academico' => $this->id_calendario,
                        'id_evento' => $evento['id'],
                        'dia_inicio_detalle_evento' => $evento['inicio'],
                        'dia_fin_detalle_evento' => $evento['fin'],
                        'estatus' => '1',
                    ]);
                }
            });

            session()->flash('message', 'Calendario aprobado y activado correctamente.');
            return redirect()->route('calendario.list');
        } catch (Exception $e) {
            session()->flash('error', 'Error al aprobar el calendario: ' . $e->getMessage());
        }
    }

    public function actualizar()
    {
        if (!Gate::allows('cambiar-estatus-calendario')) {
            abort(403);
        }

        try {
            DB::transaction(function () {
                // 1. Actualizar fechas del calendario (estatus sigue en 2)
                DB::table('calendario_academico')
                    ->where('id_calendario_academico', $this->id_calendario)
                    ->update([
                        'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                        'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                    ]);

                // 2. Limpiar eventos previos y guardar los actuales
                DB::table('detalle_evento')
                    ->where('id_calendario_academico', $this->id_calendario)
                    ->delete();

                foreach ($this->eventosRegistrados as $evento) {
                    DB::table('detalle_evento')->insert([
                        'id_calendario_academico' => $this->id_calendario,
                        'id_evento' => $evento['id'],
                        'dia_inicio_detalle_evento' => $evento['inicio'],
                        'dia_fin_detalle_evento' => $evento['fin'],
                        'estatus' => '1',
                    ]);
                }
            });

            session()->flash('message', 'Calendario actualizado (guardado como revisión).');
            return redirect()->route('calendario.list');
        } catch (Exception $e) {
            session()->flash('error', 'Error al actualizar el calendario: ' . $e->getMessage());
        }
    }

    public function cancelar()
    {
        return redirect()->route('calendario.list');
    }

    public function render()
    {
        return view('livewire.pages.calendario.editar-calendario');
    }
}

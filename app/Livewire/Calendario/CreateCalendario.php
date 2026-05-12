<?php

namespace App\Livewire\Calendario;

use Livewire\Component;
use App\Livewire\Forms\Calendario\CreateCalendarioForm;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Repositories\Evento\EventoIndexRepo;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class CreateCalendario extends Component
{
    public CreateCalendarioForm $form;
    protected $calendarioRepository;

    public $eventosRegistrados = [];
    public $bibliotecaEventos = [];
    public $currentYear;

    public $id_calendario_borrador = null;

    public function boot()
    {
        $this->calendarioRepository = new CalendarioCreateRepo();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName == 'form.dia_inicio_calendario_academico' || $propertyName == 'form.dia_fin_calendario_academico') {
            $this->filtrarEventosFueraDeRango();
            $this->guardarBorrador();
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

    public function mount($id = null)
    {
        $this->currentYear = date('Y');

        if ($id) {
            $this->id_calendario_borrador = $id;
            $calendario = DB::table('calendario_academico')
                ->where('id_calendario_academico', $id)
                ->first();

            if ($calendario) {
                $this->form->dia_inicio_calendario_academico = $calendario->dia_inicio_calendario_academico;
                $this->form->dia_fin_calendario_academico = $calendario->dia_fin_calendario_academico;
                
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
            }
        }

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

        // Validación para evitar seleccionar un evento dos veces (solo si NO es repetible)
        foreach ($this->eventosRegistrados as $evento) {
            if (isset($evento['id']) && $evento['id'] == $id_evento) {
                // Buscar si es repetible en la biblioteca
                $eventoInfo = collect($this->bibliotecaEventos)->firstWhere('id_evento', $id_evento);
                if (!$eventoInfo || !$eventoInfo->is_repetible_evento) {
                    return; // Ya está registrado y no es repetible
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

        $this->guardarBorrador();
    }

    public function removerEvento($index)
    {
        if (isset($this->eventosRegistrados[$index])) {
            unset($this->eventosRegistrados[$index]);
            $this->eventosRegistrados = array_values($this->eventosRegistrados);
            $this->guardarBorrador();
        }
    }

    protected function guardarBorrador()
    {
        // Solo guardamos si tenemos al menos una fecha
        if (!$this->form->dia_inicio_calendario_academico && !$this->form->dia_fin_calendario_academico) {
            return;
        }

        try {
            DB::transaction(function () {
                $data = [
                    'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                    'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
                    'semana_calendario_academico' => 0,
                    'estatus' => '4' // Incompleto
                ];

                if ($this->form->dia_inicio_calendario_academico && $this->form->dia_fin_calendario_academico) {
                    $inicio = \Carbon\Carbon::parse($this->form->dia_inicio_calendario_academico);
                    $fin = \Carbon\Carbon::parse($this->form->dia_fin_calendario_academico);
                    $data['semana_calendario_academico'] = ceil(($inicio->diffInDays($fin) + 1) / 7);
                }

                if ($this->id_calendario_borrador) {
                    DB::table('calendario_academico')
                        ->where('id_calendario_academico', $this->id_calendario_borrador)
                        ->update($data);
                } else {
                    $this->id_calendario_borrador = DB::table('calendario_academico')->insertGetId($data);
                }

                // Guardar eventos
                DB::table('detalle_evento')
                    ->where('id_calendario_academico', $this->id_calendario_borrador)
                    ->delete();

                foreach ($this->eventosRegistrados as $evento) {
                    DB::table('detalle_evento')->insert([
                        'id_calendario_academico' => $this->id_calendario_borrador,
                        'id_evento' => $evento['id'],
                        'dia_inicio_detalle_evento' => $evento['inicio'],
                        'dia_fin_detalle_evento' => $evento['fin'],
                        'estatus' => '1'
                    ]);
                }
            });
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error guardando borrador: ' . $e->getMessage());
        }
    }

    public function validarSeccionFechas()
    {
        $this->form->validate();
        $this->dispatch('seccion-fechas-validada');
    }

    public function save()
    {
        if (!Gate::allows('crear-calendario')) {
            abort(403);
        }

        $this->resetErrorBag();

        $validador = \Illuminate\Support\Facades\Validator::make(
            $this->form->all(),
            $this->form->rules(),
            $this->form->messages()
        );

        $hayErrorValidacion = false;

        if ($validador->fails()) {
            foreach ($validador->errors()->messages() as $campo => $mensajes) {
                $this->addError("form.$campo", $mensajes[0]);
            }
            $hayErrorValidacion = true;
        }

        if (count($this->eventosRegistrados) === 0) {
            $this->addError('eventosRegistrados', 'Debe registrar al menos un evento antes de guardar el calendario.');
            $hayErrorValidacion = true;
        }

        if ($hayErrorValidacion) {
            if ($this->getErrorBag()->hasAny(['form.dia_inicio_calendario_academico', 'form.dia_fin_calendario_academico'])) {
                $this->dispatch('abrir-seccion', section: 'fechas');
            } else {
                $this->dispatch('abrir-seccion', section: 'eventos');
            }
            return;
        }

        try {
            $id = $this->calendarioRepository->crearConEventos([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
            ], $this->eventosRegistrados, $this->id_calendario_borrador);

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

    public function cancelar()
    {
        return redirect()->route('calendario.list');
    }

    public function render()
    {
        return view('livewire.pages.calendario.create-calendario');
    }
}

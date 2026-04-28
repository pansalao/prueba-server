<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Models\CalendarioAcademico;
use App\Repositories\Evento\EventoCreateRepo;
use App\Repositories\Evento\EventoIndexRepo;
use App\Livewire\Forms\Auth\CalendarioForm;
use App\Repositories\UsuarioRepository;
use Carbon\Carbon;

class SeleccionarRol extends Component
{
    public $misRoles = [];
    public bool $hayCalendarioActivo = false;
    public bool $tieneRol3 = false;
    public bool $sistemaInactivo = false;

    protected $usuarioRepository;

    // Campos del formulario de calendario
    public CalendarioForm $form;
    public $eventosRegistrados = [];
    public $bibliotecaEventos = [];
    public $paso = 1; // Wizard step
    public $currentYear;

    #[Layout('layouts.guest')]
    public function mount()
    {
        $this->currentYear = date('Y');
        // Revisamos si ya está autenticado
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Buscamos la cédula temporal de la sesión
        $cedula = session('temp_cedula');

        if (!$cedula) {
            return redirect()->route('login');
        }

        $this->usuarioRepository = new UsuarioRepository();

        // Obtener todos sus roles en emulación
        $this->misRoles = $this->usuarioRepository->getRolesPorCedula($cedula);

        if (count($this->misRoles) === 0) {
            return redirect()->route('login');
        }

        // Verificar si existe calendario activo
        CalendarioAcademico::inactivarVencidos();
        $repo = new CalendarioCreateRepo();
        $this->hayCalendarioActivo = $repo->hayCalendarioActivo();

        if (!$this->hayCalendarioActivo) {
            // Verificar si el usuario tiene rol 3
            $this->tieneRol3 = $this->misRoles->contains('usu_cod_rol', 3);

            if (!$this->tieneRol3) {
                $this->sistemaInactivo = true;
            } else {
                // Si tiene rol 3, cargar la biblioteca de eventos (templates) con sus colores desde el repositorio
                $eventoRepo = new EventoIndexRepo();
                $this->bibliotecaEventos = $eventoRepo->obtenerBiblioteca();
            }
        }
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

    public function updated($propertyName)
    {
        $field = str_replace('form.', '', $propertyName);
        if (in_array($field, ['dia_inicio_calendario_academico', 'dia_fin_calendario_academico'])) {
            $this->form->validateOnly($field);
        }
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
        $this->eventosRegistrados = []; // Limpiar eventos registrados al volver al paso 1
    }

    /**
     * Guarda el calendario académico (solo para usuarios con rol 3).
     */
    public function guardarCalendario()
    {
        // Verificación de seguridad: solo rol 3
        $cedula = session('temp_cedula');
        if (!$cedula)
            return;

        $usuarioRepo = new UsuarioRepository();
        $tieneRol3 = $usuarioRepo->tieneRol3($cedula);

        if (!$tieneRol3) {
            session()->flash('error', 'No tiene permisos para realizar esta acción.');
            return;
        }

        if (count($this->eventosRegistrados) === 0) {
            session()->flash('error', 'Debe registrar al menos un evento u observación antes de guardar el calendario.');
            return;
        }

        // Verificar nuevamente que no exista calendario activo (evitar duplicados)
        $repo = new CalendarioCreateRepo();
        if ($repo->hayCalendarioActivo()) {
            session()->flash('error', 'Ya existe un calendario activo configurado.');
            $this->hayCalendarioActivo = true;
            return;
        }

        try {
            $this->form->validate();
            $repo = new CalendarioCreateRepo();

            $exito = $repo->crearConEventos([
                'dia_inicio_calendario_academico' => $this->form->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->form->dia_fin_calendario_academico,
            ], $this->eventosRegistrados);

            if ($exito) {
                $this->hayCalendarioActivo = true;
                session()->flash('message', 'Calendario académico y eventos creados exitosamente. Ahora seleccione su rol.');
            } else {
                session()->flash('error', 'No se pudo crear el calendario.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el calendario y sus eventos: ' . $e->getMessage());
        }
    }

    /**
     * Hace el Auth final con el rol seleccionado.
     */
    public function seleccionarRol($rolId)
    {
        $cedula = session('temp_cedula');

        if (!$cedula)
            return;

        // Verificar que haya calendario activo antes de permitir seleccionar rol
        $repo = new CalendarioCreateRepo();
        if (!$repo->hayCalendarioActivo()) {
            session()->flash('error', 'Debe existir un calendario activo para ingresar al sistema.');
            return;
        }

        // Buscamos el usu_codigo específico en emulación para esa combinación
        $usuarioRepo = new UsuarioRepository();
        $usu_codigo = $usuarioRepo->getUsuCodigo($cedula, $rolId);

        if ($usu_codigo) {
            $usuario = User::on('emulacion_sogac_2')->find($usu_codigo);

            if ($usuario) {
                session(['active_role' => $rolId]);
                Auth::login($usuario);
                session()->forget('temp_cedula');

                return redirect()->intended(route('dashboard'));
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.seleccionar-rol');
    }
}

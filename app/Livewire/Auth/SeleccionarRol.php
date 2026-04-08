<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Models\CalendarioAcademico;
use Carbon\Carbon;

class SeleccionarRol extends Component
{
    public $misRoles = [];
    public bool $hayCalendarioActivo = false;
    public bool $tieneRol3 = false;
    public bool $sistemaInactivo = false;

    // Campos del formulario de calendario
    public $dia_inicio_calendario_academico = '';
    public $dia_fin_calendario_academico = '';

    #[Layout('layouts.guest')]
    public function mount()
    {
        // Revisamos si ya está autenticado
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Buscamos la cédula temporal de la sesión
        $cedula = session('temp_cedula');

        if (!$cedula) {
            return redirect()->route('login');
        }

        // Obtener todos sus roles en emulación
        $this->misRoles = DB::connection('emulacion_sogac_2')
            ->table('usuario as u')
            ->join('rol as r', 'u.usu_cod_rol', '=', 'r.rol_codigo')
            ->where('u.usu_cedula', $cedula)
            ->where('u.usu_estatus', 'A')
            ->select('u.usu_cod_rol', 'r.rol_nombre')
            ->get();

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
            }
        }
    }

    /**
     * Guarda el calendario académico (solo para usuarios con rol 3).
     */
    public function guardarCalendario()
    {
        // Verificación de seguridad: solo rol 3
        $cedula = session('temp_cedula');
        if (!$cedula) return;

        $tieneRol3 = DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_cedula', $cedula)
            ->where('usu_cod_rol', 3)
            ->where('usu_estatus', 'A')
            ->exists();

        if (!$tieneRol3) {
            session()->flash('error', 'No tiene permisos para realizar esta acción.');
            return;
        }

        // Validar campos
        $this->validate([
            'dia_inicio_calendario_academico' => ['required', 'date'],
            'dia_fin_calendario_academico' => ['required', 'date', 'after_or_equal:dia_inicio_calendario_academico'],
        ], [
            'dia_inicio_calendario_academico.required' => 'La fecha de inicio es obligatoria.',
            'dia_inicio_calendario_academico.date' => 'La fecha de inicio debe ser válida.',
            'dia_fin_calendario_academico.required' => 'La fecha de fin es obligatoria.',
            'dia_fin_calendario_academico.date' => 'La fecha de fin debe ser válida.',
            'dia_fin_calendario_academico.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
        ]);

        // Verificar nuevamente que no exista calendario activo (evitar duplicados)
        $repo = new CalendarioCreateRepo();
        if ($repo->hayCalendarioActivo()) {
            session()->flash('error', 'Ya existe un calendario activo configurado.');
            $this->hayCalendarioActivo = true;
            return;
        }

        // Calcular semanas
        $inicio = Carbon::parse($this->dia_inicio_calendario_academico);
        $fin = Carbon::parse($this->dia_fin_calendario_academico);
        $diferenciaDias = $inicio->diffInDays($fin) + 1;
        $semanas = ceil($diferenciaDias / 7);

        try {
            $id = $repo->crear([
                'semana_calendario_academico' => $semanas,
                'dia_inicio_calendario_academico' => $this->dia_inicio_calendario_academico,
                'dia_fin_calendario_academico' => $this->dia_fin_calendario_academico,
            ]);

            if ($id) {
                $this->hayCalendarioActivo = true;
                session()->flash('message', 'Calendario académico creado exitosamente. Ahora seleccione su rol.');
            } else {
                session()->flash('error', 'No se pudo crear el calendario.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el calendario: ' . $e->getMessage());
        }
    }

    /**
     * Hace el Auth final con el rol seleccionado.
     */
    public function seleccionarRol($rolId)
    {
        $cedula = session('temp_cedula');

        if (!$cedula) return;

        // Verificar que haya calendario activo antes de permitir seleccionar rol
        $repo = new CalendarioCreateRepo();
        if (!$repo->hayCalendarioActivo()) {
            session()->flash('error', 'Debe existir un calendario activo para ingresar al sistema.');
            return;
        }

        // Buscamos el usu_codigo específico en emulación para esa combinación
        $usu_codigo = DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_cedula', $cedula)
            ->where('usu_cod_rol', $rolId)
            ->where('usu_estatus', 'A')
            ->value('usu_codigo');

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

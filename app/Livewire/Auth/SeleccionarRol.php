<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Repositories\Calendario\CalendarioCreateRepo;
use App\Models\CalendarioAcademico;
use App\Repositories\UsuarioRepository;

class SeleccionarRol extends Component
{
    public $misRoles = [];
    public $nombreUsuario = '';
    public bool $hayCalendarioActivo = false;

    protected $usuarioRepository;

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

        $this->usuarioRepository = new UsuarioRepository();

        // Obtener todos sus roles en emulación
        $this->misRoles = $this->usuarioRepository->getRolesPorCedula($cedula);

        if (count($this->misRoles) === 0) {
            return redirect()->route('login');
        }

        // Obtener el nombre del usuario (de cualquier perfil ya que comparten cédula)
        $userProfile = User::on('emulacion_sogac_2')
            ->where('usu_cedula', $cedula)
            ->first();

        if ($userProfile && $userProfile->persona) {
            $primerNombre = explode(' ', trim($userProfile->persona->per_nombres))[0];
            $primerApellido = explode(' ', trim($userProfile->persona->per_apellidos))[0];
            $this->nombreUsuario = $primerNombre . ' ' . $primerApellido;
        } else {
            $this->nombreUsuario = $userProfile ? $userProfile->name : 'Usuario';
        }

        // Verificar si existe calendario activo
        CalendarioAcademico::inactivarVencidos();
        $repo = new CalendarioCreateRepo();
        $this->hayCalendarioActivo = $repo->hayCalendarioActivo();
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
        /* $repo = new CalendarioCreateRepo();
        if (!$repo->hayCalendarioActivo() && $rolId != 3) {
            session()->flash('error', 'Debe existir un calendario activo para ingresar al sistema. Contacte al administrador.');
            return;
        } */

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

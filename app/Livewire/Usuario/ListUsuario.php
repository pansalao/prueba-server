<?php

namespace App\Livewire\Usuario;

use Livewire\Component;
use App\Models\User; // Usamos el modelo User si es necesario para acciones directas, aunque el repo ya lo maneja
use Livewire\WithPagination;
use App\Repositories\UsuarioRepository; // Importamos el UserRepository
use Exception;

class ListUsuario extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;
    protected $userRepository; // Cambiamos a userRepository

    public function __construct()
    {
        $this->userRepository = new UsuarioRepository(); // Instanciamos UsuarioRepository
    }

    // Escucha eventos si los necesitas para refrescar la lista de usuarios, por ejemplo, después de crear/editar un usuario.
    protected $listeners = ['userUpdated' => 'refreshUsers'];

    // Método para recargar los usuarios después de una actualización
    public function refreshUsers()
    {
        $this->resetPage();
    }

    public function updatedBusqueda()
    {
        $this->resetPage(); // Reinicia la paginación a la primera página
    }

    public function cambiarEstatusUsuario($userId)
    {
        if (!auth()->user()?->esCoordinadorOVicerrector()) {
            abort(403);
        }
        try {
            $user = User::findOrFail($userId);
            $user->estatus = ($user->estatus == 1) ? 2 : 1;
            $user->save();
            $data = ['tipo' => 'exitoso', 'color' => 'green', 'mensaje' => 'Estatus del usuario actualizado correctamente.'];
            $this->dispatch('mostrar-mensaje', $data);
        } catch (Exception $e) {
            $data = ['tipo' => 'error', 'mensaje' => 'Error inténtelo de nuevo'];
            $this->dispatch('mostrar-mensaje', $data);
        };
    }

    public function updatedPaginacion()
    {
        $this->resetPage(); // Reinicia la paginación a la primera página
    }

    public function render()
    {
        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5; // Vuelve al valor por defecto si no es válido
        }

        $users = $this->userRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.usuario.list-usuario', compact('users')); // Cambiamos 'pnfs' a 'users'
    }
}

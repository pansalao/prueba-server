<?php

namespace App\Livewire\Rol;

use Livewire\Component;
use App\Repositories\Rol\RolPermisoRepo;

class UpdateRolPermisos extends Component
{
    public $rolId;
    public $rol;
    public $modulosPermisos = [];
    public $selectedPermisos = [];

    protected $rolPermisoRepo;

    public function boot(RolPermisoRepo $rolPermisoRepo)
    {
        $this->rolPermisoRepo = $rolPermisoRepo;
    }

    public function mount($rolId)
    {
        $this->rolId = $rolId;
        $this->rol = $this->rolPermisoRepo->getRol($this->rolId);

        if (!$this->rol) {
            abort(404, 'Rol no encontrado en DAECE');
        }

        $permisosRaw = $this->rolPermisoRepo->getActivePermissions();
        $this->modulosPermisos = $this->groupPermissionsByModule($permisosRaw);

        $this->selectedPermisos = array_map('strval', $this->rolPermisoRepo->getRolePermissions($this->rolId));
    }

    private function groupPermissionsByModule($permisos)
    {
        $modules = [];

        foreach ($permisos as $p) {
            if (empty($p->nombre_permiso)) {
                continue;
            }

            // Lógica de agrupación inteligente:
            if (str_contains(strtolower($p->nombre_permiso), ' de ')) {
                $parts = explode(' de ', $p->nombre_permiso);
                $module = ucwords(trim(array_pop($parts))); // El último elemento es el módulo
                $action = trim(implode(' de ', $parts));    // Lo anterior es la acción
            } else {
                // Si no hay ' de ', dividimos por el último espacio.
                $parts = explode(' ', trim($p->nombre_permiso));
                if (count($parts) < 2) {
                    $module = 'General';
                    $action = $p->nombre_permiso;
                } else {
                    $module = ucwords(trim(array_pop($parts))); // La última palabra es el módulo
                    $action = trim(implode(' ', $parts));     // Lo anterior es la acción
                }
            }

            $modules[$module][] = [
                'id' => $p->id_permiso,
                'action' => $action,
                'full_name' => $p->nombre_permiso,
                'estatus' => $p->estatus
            ];
        }

        // Ordenamos alfabéticamente los módulos
        ksort($modules);

        return $modules;
    }

    public function savePermisos()
    {
        // Filtramos valores nulos/vacíos en caso de que Alpine/Livewire los envíe
        $permisosToSave = array_filter($this->selectedPermisos, function ($val) {
            return !empty($val) && $val !== false && $val !== 'false';
        });

        $success = $this->rolPermisoRepo->saveRolePermissions($this->rolId, array_values($permisosToSave));

        if ($success) {
            session()->flash('message', 'Permisos actualizados correctamente para el rol: ' . $this->rol->rol_nombre);
        } else {
            session()->flash('error', 'Ocurrió un error al actualizar los permisos.');
        }

        return redirect()->route('rol/listar');
    }


    public function render()
    {
        return view('livewire.pages.rol.update-rol-permisos');
    }
}

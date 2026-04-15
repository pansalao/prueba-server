<?php

namespace App\Livewire\Permiso;

use Livewire\Component;
use App\Repositories\Permiso\PermisoGestionRepo;

class UpdatePermiso extends Component
{
    public $permisoId;
    public $elemento;
    public $modulosPermisos = [];
    public $selectedPermisos = [];

    protected $permisoGestionRepo;

    public function boot(PermisoGestionRepo $permisoGestionRepo)
    {
        $this->permisoGestionRepo = $permisoGestionRepo;
    }

    public function mount($permisoId)
    {
        $this->permisoId = $permisoId;
        $this->elemento = $this->permisoGestionRepo->getPermitible($this->permisoId);

        if (!$this->elemento) {
            abort(404, 'Elemento no encontrado');
        }

        $permisosRaw = $this->permisoGestionRepo->getActivePermissions();
        $this->modulosPermisos = $this->groupPermissionsByModule($permisosRaw);

        $this->selectedPermisos = array_map('strval', $this->permisoGestionRepo->getPermitiblePermissions($this->permisoId));
    }

    private function groupPermissionsByModule($permisos)
    {
        $modules = [];

        foreach ($permisos as $p) {
            if (empty($p->nombre_permiso)) {
                continue;
            }

            if (str_contains(strtolower($p->nombre_permiso), ' de ')) {
                $parts = explode(' de ', $p->nombre_permiso);
                $module = ucwords(trim(array_pop($parts))); 
                $action = trim(implode(' de ', $parts));    
            } else {
                $parts = explode(' ', trim($p->nombre_permiso));
                if (count($parts) < 2) {
                    $module = 'General';
                    $action = $p->nombre_permiso;
                } else {
                    $module = ucwords(trim(array_pop($parts))); 
                    $action = trim(implode(' ', $parts));     
                }
            }

            $modules[$module][] = [
                'id' => $p->id_permiso,
                'action' => $action,
                'full_name' => $p->nombre_permiso,
                'estatus' => $p->estatus
            ];
        }

        ksort($modules);

        return $modules;
    }

    public function savePermisos()
    {
        $permisosToSave = array_filter($this->selectedPermisos, function ($val) {
            return !empty($val) && $val !== false && $val !== 'false';
        });

        $success = $this->permisoGestionRepo->savePermitiblePermissions($this->permisoId, array_values($permisosToSave));

        if ($success) {
            session()->flash('message', 'Permisos actualizados correctamente.');
        } else {
            session()->flash('error', 'Ocurrió un error al actualizar los permisos.');
        }

        return redirect()->route('permiso/listar');
    }

    public function render()
    {
        return view('livewire.pages.permiso.update-permiso');
    }
}

<?php

namespace App\Livewire\Permiso;

use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Permiso\PermisoIndexRepo;
use Illuminate\Support\Str;

class ListPermiso extends Component
{
    use WithPagination;

    public $busqueda = '';
    public $paginacion = 5;

    protected $permisoRepository;

    /**
     * Inicialización del repositorio. 
     */
    public function boot()
    {
        $this->permisoRepository = new PermisoIndexRepo();
    }

    /**
     * Orquestador de la sincronización de las rutas del sistema como permisos.
     */
    private function sincronizarPermisos()
    {
        try {
            $webContent = file_get_contents(base_path('routes/web.php'));

            // Regex para buscar Route::get('uri', ...)
            preg_match_all("/Route::get\(['\"]([^'\"]+)['\"]/i", $webContent, $matches);
            $uris = array_unique($matches[1] ?? []);

            $accionesTraducidas = [
                'list' => 'Listar de',
                'create' => 'Crear de',
                'update' => 'Editar de',
                'show' => 'Ver Detalles de',
                'reporte-general' => 'Reporte General de',
                'reporte-detalle' => 'Reporte Detallado de',
            ];

            $ignorados = ['dashboard', 'profile', 'login', 'logout', 'register', 'password', '/', '#'];
            $permisosEncontrados = [];
            $modulosEncontrados = [];

            foreach ($uris as $uri) {
                // Limpiar parámetros {id} y separar en partes
                $uriLimpia = trim(preg_replace('/\{\w+\}/', '', $uri), '/');
                if (empty($uriLimpia))
                    continue;

                $partes = explode('/', $uriLimpia);
                $moduloSlug = $partes[0];

                if (in_array($moduloSlug, $ignorados))
                    continue;

                $accionSlug = $partes[1] ?? 'index';

                $moduloNombre = Str::title(str_replace('-', ' ', $moduloSlug));
                $accionNombre = $accionesTraducidas[$accionSlug] ?? Str::title(str_replace('-', ' ', $accionSlug));

                if (!str_ends_with(strtolower($accionNombre), ' de')) {
                    $accionNombre .= " de";
                }

                $nombrePermiso = trim("{$accionNombre} {$moduloNombre}");
                $permisosEncontrados[] = $nombrePermiso;

                $this->permisoRepository->upsertPermiso($nombrePermiso);
                $modulosEncontrados[$moduloNombre] = true;
            }

            foreach (array_keys($modulosEncontrados) as $moduloNombre) {
                $permisoEstatus = "Cambiar Estatus de {$moduloNombre}";
                $permisosEncontrados[] = $permisoEstatus;
                $this->permisoRepository->upsertPermiso($permisoEstatus);
            }

            $this->permisoRepository->inactivarObsoletos($permisosEncontrados);

        } catch (\Exception $e) {
            // Silencioso
        }
    }

    public function render()
    {
        $this->sincronizarPermisos();

        $paginacionCorrecta = [5, 10, 25, 50];
        if (!in_array($this->paginacion, $paginacionCorrecta)) {
            $this->paginacion = 5;
        }

        $permisos = $this->permisoRepository->listar($this->busqueda, $this->paginacion);

        return view('livewire.pages.permiso.list-permiso', compact('permisos'));
    }
}

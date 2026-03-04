<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // Importa la fachada de Gate
use App\Repositories\AccesoRepository; // Importa tu repositorio de acceso
use Illuminate\Support\Facades\Auth; // Necesario para Auth::check() dentro del Gate
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * Aquí es donde definirás tus Gates (Puertas) y otras lógicas de autorización.
     */
    public function boot(): void
    {
        // Obtén una instancia de AcesoRepository a través del contenedor de servicios de Laravel.
        // Laravel inyectará automáticamente las dependencias necesarias.
        $accesoRepository = app(AccesoRepository::class);


        // --- GATES PARA EL MÓDULO DE EVENTO ---
        Gate::define('listar-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar Evento');
        });

        Gate::define('crear-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear Evento');
        });

        Gate::define('editar-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar Evento');
        });

        Gate::define('ver-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Evento');
        });
        // --------------------------------------

        // --- GATES PARA EL MÓDULO DE PLANIFICACIÓN ---
        Gate::define('listar-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar Planificacion');
        });

        Gate::define('crear-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear Planificacion');
        });

        Gate::define('editar-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar Planificacion');
        });

        Gate::define('ver-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Planificacion');
        });

        // ---------------------------------------------
        // NOTA: Si ya habías creado directivas personalizadas en AppServiceProvider (ej. @ifcoordinador),
        // y ahora vas a usar Gates (@can), puedes considerar eliminar esas directivas duplicadas para evitar redundancia.
    }
}

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
            return $accesoRepository->checkPermission('Listar de Evento');
        });

        Gate::define('crear-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Evento');
        });

        Gate::define('editar-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Evento');
        });

        Gate::define('ver-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Evento');
        });

        Gate::define('cambiar-estatus-evento', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Evento');
        });
        // --------------------------------------

        // --- GATES PARA EL MÓDULO DE CALENDARIO ---
        Gate::define('listar-calendario', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Calendario');
        });

        Gate::define('crear-calendario', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Calendario');
        });

        Gate::define('editar-calendario', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Calendario');
        });

        Gate::define('ver-calendario', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Calendario');
        });

        Gate::define('cambiar-estatus-calendario', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Calendario');
        });
        // --------------------------------------

        // --- GATES PARA EL MÓDULO DE CONTENIDO ---
        Gate::define('listar-contenido', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Contenido');
        });
        Gate::define('crear-contenido', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Contenido');
        });
        Gate::define('editar-contenido', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Contenido');
        });
        Gate::define('ver-contenido', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Contenido');
        });
        Gate::define('cambiar-estatus-contenido', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Contenido');
        });

        // --- GATES PARA EL MÓDULO DE TEMA ---
        Gate::define('listar-tema', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Tema');
        });
        Gate::define('crear-tema', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Tema');
        });
        Gate::define('editar-tema', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Tema');
        });
        Gate::define('ver-tema', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Tema');
        });
        Gate::define('cambiar-estatus-tema', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Tema');
        });

        // --- GATES PARA EL MÓDULO DE INDICADOR LOGRO ---
        Gate::define('listar-indicador-logro', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Indicador Logro');
        });
        Gate::define('crear-indicador-logro', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Indicador Logro');
        });
        Gate::define('editar-indicador-logro', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Indicador Logro');
        });
        Gate::define('ver-indicador-logro', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Indicador Logro');
        });
        Gate::define('cambiar-estatus-indicador-logro', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Indicador Logro');
        });

        // --- GATES PARA EL MÓDULO DE BIBLIOGRAFIA ---
        Gate::define('listar-bibliografia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Bibliografia');
        });
        Gate::define('crear-bibliografia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Bibliografia');
        });
        Gate::define('editar-bibliografia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Bibliografia');
        });
        Gate::define('ver-bibliografia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Bibliografia');
        });
        Gate::define('cambiar-estatus-bibliografia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Bibliografia');
        });

        // --- GATES PARA EL MÓDULO DE RECURSO ---
        Gate::define('listar-recurso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Recurso');
        });
        Gate::define('crear-recurso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Recurso');
        });
        Gate::define('editar-recurso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Recurso');
        });
        Gate::define('ver-recurso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Recurso');
        });
        Gate::define('cambiar-estatus-recurso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Recurso');
        });

        // --- GATES PARA EL MÓDULO DE ESTRATEGIA ---
        Gate::define('listar-estrategia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Estrategia');
        });
        Gate::define('crear-estrategia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Estrategia');
        });
        Gate::define('editar-estrategia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Estrategia');
        });
        Gate::define('ver-estrategia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Estrategia');
        });
        Gate::define('cambiar-estatus-estrategia', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Estrategia');
        });


        // --- GATES PARA EL MÓDULO DE EVALUACION ---
        Gate::define('listar-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Tecnica Evaluacion');
        });
        Gate::define('crear-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Tecnica Evaluacion');
        });
        Gate::define('editar-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Tecnica Evaluacion');
        });
        Gate::define('ver-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Tecnica Evaluacion');
        });
        Gate::define('cambiar-estatus-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Tecnica Evaluacion');
        });

        // --- GATES PARA EL MÓDULO DE TIPO EVALUACION ---
        Gate::define('listar-tipo-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Tipo Evaluacion');
        });
        Gate::define('crear-tipo-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Tipo Evaluacion');
        });
        Gate::define('editar-tipo-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Tipo Evaluacion');
        });
        Gate::define('ver-tipo-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Tipo Evaluacion');
        });
        Gate::define('cambiar-estatus-tipo-evaluacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Tipo Evaluacion');
        });

        // --- GATES PARA EL MÓDULO DE ROL ---
        Gate::define('listar-rol', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Rol');
        });
        Gate::define('editar-rol', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Rol');
        });

        // --- GATES PARA EL MÓDULO DE PERMISO ---
        Gate::define('listar-permiso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Permiso');
        });
        Gate::define('editar-permiso', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Permiso');
        });

        // --- GATES PARA EL MÓDULO DE BITACORA ---
        Gate::define('listar-bitacora', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Bitacora');
        });
        Gate::define('ver-bitacora', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Bitacora');
        });

        // --- GATES PARA EL MÓDULO DE PLANIFICACIÓN ---
        Gate::define('listar-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Listar de Planificacion');
        });

        Gate::define('crear-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Crear de Planificacion');
        });

        Gate::define('editar-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Editar de Planificacion');
        });

        Gate::define('ver-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Ver Detalles de Planificacion');
        });

        Gate::define('cambiar-estatus-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Planificacion');
        });

        Gate::define('aprobacion-vocero-planificacion', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Aprobacion Vocero de Planificacion');
        });

        // --- GATES PARA EL MÓDULO DE FIRMA ---
        Gate::define('mi-firma', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Mi Firma de Firma');
        });

        // --- GATES PARA EL MÓDULO DE VOCEROS ---
        Gate::define('cambiar-estatus-voceros', function ($user) use ($accesoRepository) {
            return $accesoRepository->checkPermission('Cambiar Estatus de Voceros');
        });
        // NOTA: Si ya habías creado directivas personalizadas en AppServiceProvider (ej. @ifcoordinador),
        // y ahora vas a usar Gates (@can), puedes considerar eliminar esas directivas duplicadas para evitar redundancia.
    }
}

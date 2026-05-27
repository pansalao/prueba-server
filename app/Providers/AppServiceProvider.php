<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        $this->configureDynamicConnection();
    }

    /**
     * Configura la conexión de forma perezosa (Lazy).
     * Esto SOLUCIONA LA LENTITUD DEL SISTEMA, ya que NO prueba la conexión
     * cada vez que se carga cualquier página, sino SOLO cuando se pide 'external_db'.
     */
    private function configureDynamicConnection(): void
    {
        // 1. Registramos una conexión en el sistema de manera nominal que apunta a un 'driver' falso.
        Config::set('database.connections.external_db', [
            'driver' => 'external_fallback_driver'
        ]);

        // 2. Le decimos a Laravel cómo crear las conexiones de este driver particular
        // ¡ESTE CÓDIGO SOLO SE EJECUTA SI ALGUIEN LLAMA A LA BASE DE DATOS EXTERNA!
        DB::extend('external_fallback_driver', function ($config, $name) {
            try {
                // Intentar conectar solo en este instante
                DB::connection('pgsql_daece')->getPdo();
                return DB::connection('emulacion_sogac_2');
            } catch (Exception $e) {
                Log::warning('Fallo la conexión a pgsql_daece (Conexión diferida). Usando emulacion_sogac_2. Error: ' . $e->getMessage());
                return DB::connection('emulacion_sogac_2');
            }
        });
    }
}

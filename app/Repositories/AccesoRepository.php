<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccesoRepository
{
    /**
     * Verifica si el usuario autenticado tiene un rol específico y está activo.
     *
     * @param int $rolId El ID del rol a verificar (ej. 1 para Coordinador, 2 para Profesor).
     * @return bool
     */
    public function checkRole(int $rolId): bool
    {
        // Verifica si el usuario actual está autenticado
        if (!Auth::check()) {
            return false;
        }

        // El rol ahora viene directamente del modelo User (conectado a emulacion_sogac_2)
        return Auth::user()->usu_cod_rol == $rolId && Auth::user()->usu_estatus == 'A';
    }

    /**
     * Verifica si el usuario autenticado tiene un permiso específico a través de su rol activo.
     *
     * @param string $permissionName Nombre exacto del permiso (ej: 'Listar Evento').
     * @return bool
     */
    public function checkPermission(string $permissionName): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        // Si el usuario no tiene rol o no está activo (en la BD externa 'A' es activo), no tiene permisos
        if (!$user->usu_cod_rol || $user->usu_estatus != 'A') {
            return false;
        }

        // Buscamos el permiso en la tabla local 'rol_permiso' y 'permiso' (hp_10)
        // El id_rol coincide con el usu_cod_rol de la BD externa.
        return DB::table('rol_permiso as rp')
            ->join('permiso as p', 'rp.id_permiso', '=', 'p.id_permiso')
            ->where('rp.id_rol', $user->usu_cod_rol)
            ->where('p.nombre_permiso', $permissionName)
            ->where('p.estatus', '1')         // El permiso debe existir y estar activo
            ->where('rp.estatus', '1')        // La vinculación rol-permiso debe estar activa
            ->exists();
    }

    public function checkCoordinador(): bool
    {
        return Auth::check() && in_array(Auth::user()->usu_cod_rol, [1, 5, 11]) && Auth::user()->usu_estatus == 'A';
    }

    public function checkProfesor(): bool
    {
        return $this->checkRole(2); // El rol_id para Profesor
    }
}

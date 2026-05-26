<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository
{

    public function select_roles()
    {
        return DB::connection('external_db')
            ->table('rol')
            ->select('rol_codigo as id_rol', 'rol_nombre as acceso')
            //->where('rol_estatus', 'A')
            ->get();
    }

    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::connection('external_db')
            ->table('usuario as u')
            ->join('persona as p', 'u.usu_cedula', '=', 'p.per_cedula')
            ->join('rol as r', 'u.usu_cod_rol', '=', 'r.rol_codigo')
            ->select(
                'u.usu_codigo as id',
                'p.per_nombres as name',
                'p.per_apellidos as apellido',
                'u.usu_estatus as estatus',
                'r.rol_nombre as roles_nombres'
            )
            ->where('u.usu_codigo', '!=', Auth::id())
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('p.per_nombres', 'LIKE', '%' . $busqueda . '%')
                    ->orWhere('p.per_apellidos', 'LIKE', '%' . $busqueda . '%')
                    ->orWhere('u.usu_cedula', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('u.usu_codigo', 'desc')
            ->paginate($paginacion);
    }

    public function mostrar($id)
    {
        $user = \App\Models\User::find($id);
        if ($user) {
            \App\Models\User::logMostrar($user);
        }
        return $user;
    }

    public function crear($data, $roles)
    {
        DB::beginTransaction();
        try {
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'apellido' => $data['apellido'],
                'cedula' => $data['cedula'],
                'email' => $data['email'],
                'telefono' => $data['telefono'],
                'password' => $data['password']
            ]);

            foreach ($roles as $rol) {
                DB::table('usuario_rol')->insert([
                    'id_users' => $user->id,
                    'id_rol' => $rol
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene los roles de un usuario por su cédula.
     */
    public function getRolesPorCedula(string $cedula)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('usuario as u')
            ->join('rol as r', 'u.usu_cod_rol', '=', 'r.rol_codigo')
            ->where('u.usu_cedula', $cedula)
            ->where('u.usu_estatus', 'A')
            ->select('u.usu_cod_rol', 'r.rol_nombre')
            ->get();
    }

    /**
     * Obtiene y filtra los roles activos de un usuario que pertenecen al PNF de Informática.
     */
    public function getRolesInformaticoPorCedula(string $cedula)
    {
        $allRoles = $this->getRolesPorCedula($cedula);
        $filteredRoles = [];

        foreach ($allRoles as $role) {
            $rolId = $role->usu_cod_rol;

            // 1. Coordinador PNFINF (ID: 11) - Pertenece a Informática
            if ($rolId == 11) {
                $filteredRoles[] = $role;
                continue;
            }

            // 2. Estudiante (ID: 4) - Pertenece a Informática si está inscrito en programa 4
            if ($rolId == 4) {
                $esEstudianteInf = DB::connection('emulacion_sogac_2')
                    ->table('estudiante')
                    ->where('est_cedula', $cedula)
                    ->where('est_cod_programa', 4)
                    ->exists();
                if ($esEstudianteInf) {
                    $filteredRoles[] = $role;
                }
                continue;
            }

            // 3. Docente (ID: 3) - Pertenece a Informática si tiene carga activa en programa 4
            if ($rolId == 3) {
                $esDocenteInf = DB::connection('emulacion_sogac_2')
                    ->table('seccion_unidad_docente as sud')
                    ->join('unidad_curricular as uc', 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
                    ->join('malla as m', 'uc.ucu_cod_malla', '=', 'm.mal_codigo')
                    ->where('sud.sud_ced_docente', $cedula)
                    ->where('m.mal_cod_programa', 4)
                    ->where('sud.sud_estatus', 'A')
                    ->exists();
                if ($esDocenteInf) {
                    $filteredRoles[] = $role;
                }
                continue;
            }

            // 4. Vicerrector (ID: 31) - También puede usar el sistema
            if ($rolId == 31) {
                $filteredRoles[] = $role;
                continue;
            }
        }

        return collect($filteredRoles);
    }

    /**
     * Verifica si un usuario tiene el rol 3 activo.
     */
    public function tieneRol3(string $cedula): bool
    {
        return DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_cedula', $cedula)
            ->where('usu_cod_rol', 3)
            ->where('usu_estatus', 'A')
            ->exists();
    }

    /**
     * Obtiene el código de usuario (usu_codigo) para un rol específico.
     */
    public function getUsuCodigo(string $cedula, int $rolId)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_cedula', $cedula)
            ->where('usu_cod_rol', $rolId)
            ->where('usu_estatus', 'A')
            ->value('usu_codigo');
    }
}

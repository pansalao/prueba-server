<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Traits\Auditable;

class User extends Authenticatable
{
    use Auditable;
    public $timestamps = false;

    protected $connection = 'external_db';
    protected $table = 'usuario';
    protected $primaryKey = 'usu_codigo';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'usu_nombre',
        'usu_cedula',
        'usu_clave',
        'usu_estatus',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'usu_clave',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->usu_clave;
    }

    public function getNameAttribute()
    {
        return $this->persona ? $this->persona->full_name : $this->usu_nombre;
    }

    public function getCedulaAttribute()
    {
        return $this->usu_cedula;
    }

    /**
     * Accesor para forzar que el rol provenga de la base de datos de emulación.
     */
    public function getUsuCodRolAttribute($value)
    {
        // Primero intentamos obtener el rol desde la sesión de navegación activa
        if (session()->has('active_role')) {
            return session('active_role');
        }

        try {
            // Si no hay selección manual en sesión, buscamos el rol en emulación
            $emulacionRol = \DB::connection('external_db')
                ->table('usuario')
                ->where('usu_cedula', $this->usu_cedula)
                ->where('usu_estatus', 'A')
                ->orderBy('usu_codigo', 'desc')
                ->value('usu_cod_rol');

            return $emulacionRol ?? $value;
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function getEstatusAttribute()
    {
        // En la BD externa 'A' es activo, 'I' inactivo. 
        // El sistema local espera 1 para activo.
        return ($this->usu_estatus == 'A') ? 1 : 2;
    }

    public function getEmailAttribute()
    {
        // El email real está en la tabla persona, pero como fallback usamos usu_nombre
        return $this->usu_nombre . '@sogac.com';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'usu_clave' => 'hashed',
        ];
    }

    /**
     * Relación con el modelo Rol en la base de datos externa.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'usu_cod_rol', 'rol_codigo');
    }

    /**
     * Relación con el modelo Persona en la base de datos externa.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'usu_cedula', 'per_cedula');
    }

    /**
     * Obtiene todos los perfiles activos asociados a la misma cédula
     * en la base de datos de emulación.
     */
    public function obtenerRolesAsociados()
    {
        return \DB::connection('external_db')
            ->table('usuario as u')
            ->join('rol as r', 'u.usu_cod_rol', '=', 'r.rol_codigo')
            ->where('u.usu_cedula', $this->usu_cedula)
            ->where('u.usu_estatus', 'A')
            ->select('u.usu_cod_rol', 'r.rol_nombre', 'u.usu_codigo')
            ->get();
    }

    public function esCoordinador(): bool
    {
        return in_array($this->usu_cod_rol, [1, 5, 11, 30]);
    }

    public function esVicerrector(): bool
    {
        return in_array($this->usu_cod_rol, [4, 31]);
    }

    public function esCoordinadorOVicerrector(): bool
    {
        return $this->esCoordinador() || $this->esVicerrector();
    }
}

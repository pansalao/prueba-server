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
        return $this->usu_nombre;
    }

    public function getCedulaAttribute()
    {
        return $this->usu_cedula;
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class RolPermiso extends Model
{
    use Auditable;

    protected $table = 'rol_permiso';
    protected $primaryKey = 'id_rol_permiso';
    public $timestamps = false;
    protected $guarded = [];

    // Opcional: Define nombre de módulo legible para la bitácora
    public $moduleName = 'Roles (Permisos)';
}

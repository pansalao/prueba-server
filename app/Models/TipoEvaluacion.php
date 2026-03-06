<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class TipoEvaluacion extends Model
{
    use Auditable;

    protected $table = 'tipo_evaluacion';
    protected $primaryKey = 'id_tipo_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo_evaluacion',
        'fecha_creacion',
        'fecha_actualizacion',
        'estatus'
    ];
}

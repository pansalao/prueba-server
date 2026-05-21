<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarioAcademico extends Model
{
    protected $table = 'calendario_academico';
    protected $primaryKey = 'id_calendario_academico';
    public $timestamps = false;
    protected $guarded = [];

    public static function inactivarVencidos()
    {
        return self::where('estatus', '1')
            ->whereDate('dia_fin_calendario_academico', '<', now())
            ->update(['estatus' => '3']);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEvento::class, 'id_calendario_academico');
    }
}

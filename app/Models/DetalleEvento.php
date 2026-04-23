<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class DetalleEvento extends Model
{
    use Auditable;

    protected $table = 'detalle_evento';
    protected $primaryKey = 'id_detalle_evento';
    public $timestamps = false;
    protected $guarded = [];

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'id_evento');
    }

    public function calendario()
    {
        return $this->belongsTo(CalendarioAcademico::class, 'id_calendario_academico');
    }
}

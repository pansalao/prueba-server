<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Evento extends Model
{
    use Auditable;

    protected $table = 'evento';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;
    protected $guarded = [];

    public function color_rel()
    {
        return $this->belongsTo(Color::class, 'id_color');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEvento::class, 'id_evento');
    }

    /**
     * Compatibility Accessors for old schema column names
     */
    public function getDescripcionEventoAttribute()
    {
        return $this->nombre_evento;
    }

    public function getTipoEventoNombreAttribute()
    {
        $tipos = [
            1 => 'Feriado Nacional', 
            2 => 'Administrativo/Académico - Laborable', 
            3 => 'Administrativo/Académico - No Laborable',
            4 => 'Otros'
        ];
        return $tipos[$this->tipo_evento] ?? 'Desconocido';
    }

    public function getColorAttribute()
    {
        // Map tipo_evento to a color for visual compatibility
        $colors = [1 => '#DC3545', 2 => '#007BFF', 3 => '#FD7E14', 4 => '#6c757d'];
        return $this->color_rel->codigo_color ?? ($colors[$this->tipo_evento] ?? '#6c757d');
    }
}

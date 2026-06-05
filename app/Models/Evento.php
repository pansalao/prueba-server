<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Evento extends Model
{
    use Auditable, HasFactory;

    protected $table = 'evento';
    protected $primaryKey = 'id_evento';
    public $timestamps = false;
    protected $guarded = ['id_evento'];

    protected $casts = [
        'is_laborable_evento' => 'boolean',
        'is_repetible_evento' => 'boolean',
        'is_cantidad_dias_evento' => 'boolean',
        'is_independiente' => 'boolean',
        'is_independiente_evento' => 'boolean',
        'is_superponible_evento' => 'boolean',
        'is_fin_semana_evento' => 'boolean',
        'is_semana_evento' => 'boolean',
        'is_dia_evento' => 'boolean',
        'dia_evento' => 'date',
        'semana_evento' => 'array',
        'id_especial_evento' => 'integer',
        'cantidad_dias_evento' => 'integer',
        'cantidad_repetible_evento' => 'integer',
        'justificativo_evento' => 'array',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleEvento::class, 'id_evento');
    }

    public function especialEvento()
    {
        return $this->belongsTo(EspecialEvento::class, 'id_especial_evento', 'id_especial_evento');
    }

    /**
     * Accessor para compatibilidad con código que usa el antiguo campo ENUM especial_evento
     */
    public function getEspecialEventoAttribute()
    {
        return $this->id_especial_evento ? (string) $this->id_especial_evento : null;
    }

    /**
     * Accessor para mantener compatibilidad con código que usa ->color_rel
     */
    public function getColorRelAttribute()
    {
        if ($this->codigo_color_evento) {
            return (object) [
                'codigo_color' => $this->codigo_color_evento,
                'nombre_color' => $this->codigo_color_evento,
            ];
        }
        return null;
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
            2 => 'Feriado Local', 
            3 => 'Administrativo',
            4 => 'Académico',
            5 => 'Administrativo/Académico'
        ];
        return $tipos[$this->tipo_evento] ?? 'Desconocido';
    }

    public function getColorAttribute()
    {
        // Map tipo_evento to a color for visual compatibility
        $colors = [
            1 => '#DC3545', 
            2 => '#FFC107', 
            3 => '#007BFF',
            4 => '#28A745',
            5 => '#6c757d'
        ];
        return $this->codigo_color_evento ?? ($colors[$this->tipo_evento] ?? '#6c757d');
    }

    public function getNombreColorAttribute()
    {
        return $this->codigo_color_evento ?? 'N/A';
    }
}

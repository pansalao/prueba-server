<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarioAcademico extends Model
{
    use \App\Traits\Auditable;

    protected $table = 'calendario_academico';
    protected $primaryKey = 'id_calendario_academico';
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'nota_calendario_academico' => 'array',
        'justificativo_calendario_academico' => 'array',
    ];

    public static function inactivarVencidos()
    {
        $cantidadModificada = 0;

        // Obtener el calendario activo actual
        $calendarioActivo = self::where('estatus', '1')->first();

        if ($calendarioActivo) {
            // Verificar cuál es la fecha fin del último evento de este calendario
            $ultimoEvento = $calendarioActivo->detalles()->orderBy('dia_fin_detalle_evento', 'desc')->first();

            $fechaCorte = $ultimoEvento ? $ultimoEvento->dia_fin_detalle_evento : $calendarioActivo->dia_fin_calendario_academico;

            if (now()->startOfDay()->gt(\Carbon\Carbon::parse($fechaCorte)->startOfDay())) {
                // El último evento ya pasó, inactivar el calendario
                $calendarioActivo->update(['estatus' => '3']);
                $calendarioActivo = null; // Para que pase a buscar si hay uno en espera
                $cantidadModificada++;
            }
        }

        // Se eliminó la lógica de activar calendarios en espera (estatus 4)
        // porque en el nuevo flujo de negocio solo puede existir 1 calendario a la vez.
        // El estatus 4 ahora representa estrictamente 'Incompleto / Borrador'.

        // Adicionalmente, inactivar cualquier otro calendario que se haya quedado huérfano y vencido
        $cantidadModificada += self::where('estatus', '1')
            ->whereDate('dia_fin_calendario_academico', '<', now())
            ->update(['estatus' => '3']);

        return $cantidadModificada;
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEvento::class, 'id_calendario_academico');
    }
}

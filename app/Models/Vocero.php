<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocero extends Model
{
    use HasFactory;

    protected $table = 'vocero';
    protected $primaryKey = 'id_vocero';

    protected $fillable = [
        'id_estudiante',
        'id_seccion',
        'id_pnf',
        'id_coordinador',
        'estatus',
        'tipo_vocero'
    ];

    /**
     * Relación con el usuario estudiante (DB externa).
     * Nota: Puede que requiera conexión cruzada si se quiere acceder a los datos
     */
    public function estudiante()
    {
        return $this->setConnection('emulacion_sogac_2')->belongsTo(User::class, 'id_estudiante', 'usu_cedula');
    }
}

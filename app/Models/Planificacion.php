<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Planificacion extends Model
{
    use Auditable;

    protected $table = 'planificacion';
    protected $primaryKey = 'id_planificacion';
    public $timestamps = true;
    protected $guarded = [];
}

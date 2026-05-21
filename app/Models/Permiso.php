<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Permiso extends Model
{
    use Auditable;

    protected $table = 'permiso';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;
    protected $guarded = [];
}

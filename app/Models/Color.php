<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Color extends Model
{
    use Auditable;

    protected $table = 'color';
    protected $primaryKey = 'id_color';
    public $timestamps = false;
    protected $guarded = [];
}

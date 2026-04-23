<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'color';
    protected $primaryKey = 'id_color';
    public $timestamps = false;
    protected $guarded = [];
}

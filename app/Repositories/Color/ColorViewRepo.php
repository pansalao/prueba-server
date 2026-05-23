<?php

namespace App\Repositories\Color;

use App\Models\Color;

class ColorViewRepo
{
    public function mostrar($id)
    {
        return Color::find($id);
    }
}

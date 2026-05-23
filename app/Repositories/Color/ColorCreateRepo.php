<?php

namespace App\Repositories\Color;

use App\Models\Color;

class ColorCreateRepo
{
    public function crear(array $datos)
    {
        return Color::create([
            'nombre_color' => $datos['nombre_color'],
            'codigo_color' => $datos['codigo_color'],
            'estatus' => '1'
        ]);
    }

    public function existeNombre($nombre)
    {
        return Color::where('nombre_color', $nombre)->where('estatus', '!=', '3')->exists();
    }

    public function existeCodigo($codigo)
    {
        return Color::where('codigo_color', $codigo)->where('estatus', '!=', '3')->exists();
    }
}

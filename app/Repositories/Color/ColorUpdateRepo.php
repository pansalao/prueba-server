<?php

namespace App\Repositories\Color;

use App\Models\Color;

class ColorUpdateRepo
{
    public function obtenerPorId($id)
    {
        return Color::find($id);
    }

    public function actualizar($id, array $datos)
    {
        $color = Color::find($id);
        if ($color) {
            $color->update([
                'nombre_color' => $datos['nombre_color'],
                'codigo_color' => $datos['codigo_color']
            ]);
            return $color;
        }
        return null;
    }

    public function existeNombreExcluyendo($nombre, $id)
    {
        return Color::where('nombre_color', $nombre)->where('id_color', '!=', $id)->where('estatus', '!=', '3')->exists();
    }

    public function existeCodigoExcluyendo($codigo, $id)
    {
        return Color::where('codigo_color', $codigo)->where('id_color', '!=', $id)->where('estatus', '!=', '3')->exists();
    }
}

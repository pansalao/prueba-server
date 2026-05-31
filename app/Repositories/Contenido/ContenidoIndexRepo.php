<?php

namespace App\Repositories\Contenido;

use Illuminate\Support\Facades\DB;

class ContenidoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        $contenidos = DB::table('contenido as c')
            ->join('detalle_objetivo as do', 'c.id_contenido', '=', 'do.id_contenido')
            ->join('objetivo as o', 'do.id_objetivo', '=', 'o.id_objetivo')
            ->join('tema_unidad as t', 'o.id_tema_unidad', '=', 't.id_tema_unidad')
            ->where('do.estatus', '1') // Solo considerar objetivos asociados activamente al contenido
            ->select(
                'c.id_contenido',
                'c.titulo_contenido',
                't.id_unidad_curricular',
                't.titulo_tema',
                'c.estatus'
            )
            ->groupBy('c.id_contenido', 'c.titulo_contenido', 't.id_unidad_curricular', 't.titulo_tema', 'c.estatus')
            ->when(!$esCoordinadorOVicerrector, function ($query) {
                return $query->where('c.estatus', '1');
            })
            ->when($busqueda, function ($query, $busqueda) {
                // Si hay búsqueda, buscar coincidencias en la DB externa primero
                $unidadesCoincidentes = DB::connection('external_db')->table('unidad_curricular')
                    ->where('ucu_nombre', 'LIKE', '%' . $busqueda . '%')
                    ->pluck('ucu_codigo')->toArray();

                return $query->where(function ($q) use ($busqueda, $unidadesCoincidentes) {
                    $q->where('c.titulo_contenido', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('t.titulo_tema', 'LIKE', '%' . $busqueda . '%');

                    if (!empty($unidadesCoincidentes)) {
                        $q->orWhereIn('t.id_unidad_curricular', $unidadesCoincidentes);
                    }
                });
            })
            ->orderBy('c.id_contenido', 'desc')
            ->paginate($paginacion);

        // Obtener nombres de unidades curriculares desde la base de datos externa SOGC
        $idUnidades = collect($contenidos->items())->pluck('id_unidad_curricular')->unique()->toArray();
        $unidadesSogc = DB::connection('external_db')->table('unidad_curricular')
            ->whereIn('ucu_codigo', $idUnidades)
            ->pluck('ucu_nombre', 'ucu_codigo');

        // Mapear los nombres a la colección de contenidos
        $contenidos->getCollection()->transform(function ($contenido) use ($unidadesSogc) {
            $contenido->nombre_unidad_curricular = $unidadesSogc[$contenido->id_unidad_curricular] ?? 'No definida (SOGC)';
            return $contenido;
        });

        return $contenidos;
    }

    public function inhabilitar($id)
    {
        $contenido = \App\Models\Contenido::find($id);
        if ($contenido) {
            return $contenido->update([
                'estatus' => '3'
            ]);
        }
        return false;
    }

    public function restaurar($id)
    {
        $contenido = \App\Models\Contenido::where('id_contenido', $id)->where('estatus', '3')->first();
        if ($contenido) {
            return $contenido->update([
                'estatus' => '1'
            ]);
        }
        return false;
    }
}

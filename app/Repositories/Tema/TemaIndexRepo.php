<?php

namespace App\Repositories\Tema;

use Illuminate\Support\Facades\DB;

class TemaIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        $temas = DB::table('tema_unidad as t')
            ->select(
                't.id_tema_unidad',
                't.titulo_tema',
                't.id_unidad_curricular',
                't.estatus'
            )
            ->when(!$esCoordinadorOVicerrector, function ($query) {
                return $query->where('t.estatus', '1');
            })
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where(function ($q) use ($busqueda) {
                    $q->where('t.titulo_tema', 'LIKE', '%' . $busqueda . '%')
                        // Búsqueda por objetivos asociados
                        ->orWhereExists(function ($sub) use ($busqueda) {
                            $sub->select(DB::raw(1))
                                ->from('objetivo as o')
                                ->whereColumn('o.id_tema_unidad', 't.id_tema_unidad')
                                ->where('o.titulo_objetivo', 'LIKE', '%' . $busqueda . '%')
                                ->where('o.estatus', '1');
                        });
                });
            })
            ->orderBy('t.id_tema_unidad', 'desc')
            ->paginate($paginacion);

        // Obtener nombres de unidades curriculares desde la base de datos externa SOGC
        $idUnidades = $temas->pluck('id_unidad_curricular')->unique()->toArray();
        $unidadesSogc = DB::connection('external_db')->table('unidad_curricular')
            ->whereIn('ucu_codigo', $idUnidades)
            ->pluck('ucu_nombre', 'ucu_codigo');

        // Mapear los nombres a la colección de temas
        $temas->getCollection()->transform(function ($tema) use ($unidadesSogc) {
            $tema->nombre_unidad_curricular = $unidadesSogc[$tema->id_unidad_curricular] ?? 'No definida (SOGC)';
            return $tema;
        });

        return $temas;
    }

    public function inhabilitar($id)
    {
        $tema = \App\Models\Tema::find($id);
        if ($tema) {
            return $tema->update([
                'estatus' => '3'
            ]);
        }
        return false;
    }

    public function restaurar($id)
    {
        $tema = \App\Models\Tema::where('id_tema_unidad', $id)->where('estatus', '3')->first();
        if ($tema) {
            return $tema->update([
                'estatus' => '1'
            ]);
        }
        return false;
    }
}

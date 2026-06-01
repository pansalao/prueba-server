<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Limpiamos la tabla en caso de que ya tenga datos
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('especial_evento')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $eventosEspeciales = [
            ['id_especial_evento' => 1, 'especial_evento_name' => 'Vacaciones Colectivas', 'estatus' => '1'],
            ['id_especial_evento' => 2, 'especial_evento_name' => 'Inicio del Lapso Académico', 'estatus' => '1'],
            ['id_especial_evento' => 3, 'especial_evento_name' => 'Fin del Lapso Académico', 'estatus' => '1'],
            ['id_especial_evento' => 4, 'especial_evento_name' => 'Semana Santa', 'estatus' => '1'],
            ['id_especial_evento' => 5, 'especial_evento_name' => 'Carnaval', 'estatus' => '1'],
            ['id_especial_evento' => 7, 'especial_evento_name' => 'Inicio del Lapso Académico Trayecto Inicial', 'estatus' => '1'],
            ['id_especial_evento' => 8, 'especial_evento_name' => 'Fin del Lapso Académico Trayecto Inicial', 'estatus' => '1'],
            ['id_especial_evento' => 9, 'especial_evento_name' => 'Inicio del Curso Intensivo', 'estatus' => '1'],
            ['id_especial_evento' => 10, 'especial_evento_name' => 'Fin del Curso Intensivo', 'estatus' => '1'],
            ['id_especial_evento' => 11, 'especial_evento_name' => 'Incorporación después del Receso Vacacional', 'estatus' => '1'],
            ['id_especial_evento' => 13, 'especial_evento_name' => 'Inicio del Período', 'estatus' => '1'],
            ['id_especial_evento' => 14, 'especial_evento_name' => 'Fin del Período', 'estatus' => '1'],
        ];

        DB::table('especial_evento')->insert($eventosEspeciales);
    }
}

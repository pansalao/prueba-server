<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventoAndColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desactivar restricciones de llaves foráneas para poder truncar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas previas
        DB::table('detalle_evento')->truncate();
        DB::table('evento')->truncate();
        DB::table('color')->truncate();

        // Reactivar restricciones
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insertar Colores
        $colores = [
            ['id_color' => 1, 'nombre_color' => 'Rojo', 'codigo_color' => '#DC3545', 'estatus' => '1'],
            ['id_color' => 2, 'nombre_color' => 'Azul', 'codigo_color' => '#007BFF', 'estatus' => '1'],
            ['id_color' => 3, 'nombre_color' => 'Verde', 'codigo_color' => '#28A745', 'estatus' => '1'],
            ['id_color' => 4, 'nombre_color' => 'Amarillo', 'codigo_color' => '#FFC107', 'estatus' => '1'],
            ['id_color' => 5, 'nombre_color' => 'Naranja', 'codigo_color' => '#FD7E14', 'estatus' => '1'],
            ['id_color' => 6, 'nombre_color' => 'Morado', 'codigo_color' => '#6F42C1', 'estatus' => '1'],
            ['id_color' => 7, 'nombre_color' => 'Rosa', 'codigo_color' => '#E83E8C', 'estatus' => '1'],
            ['id_color' => 8, 'nombre_color' => 'Cian', 'codigo_color' => '#17A2B8', 'estatus' => '1'],
            ['id_color' => 9, 'nombre_color' => 'Índigo', 'codigo_color' => '#6610F2', 'estatus' => '1'],
            ['id_color' => 10, 'nombre_color' => 'Gris Oscuro', 'codigo_color' => '#343A40', 'estatus' => '1'],
            ['id_color' => 11, 'nombre_color' => 'Marrón', 'codigo_color' => '#795548', 'estatus' => '1'],
            ['id_color' => 12, 'nombre_color' => 'Morado Claro', 'codigo_color' => '#6F42C1', 'estatus' => '1'],
            ['id_color' => 13, 'nombre_color' => 'Rosa Suave', 'codigo_color' => '#E83E8C', 'estatus' => '1'],
            ['id_color' => 14, 'nombre_color' => 'Cian Brillante', 'codigo_color' => '#17A2B8', 'estatus' => '1'],
            ['id_color' => 15, 'nombre_color' => 'Índigo Oscuro', 'codigo_color' => '#6610F2', 'estatus' => '1'],
            ['id_color' => 16, 'nombre_color' => 'Antracita', 'codigo_color' => '#343A40', 'estatus' => '1'],
            ['id_color' => 17, 'nombre_color' => 'Café', 'codigo_color' => '#795548', 'estatus' => '1'],
        ];

        DB::table('color')->insert($colores);

        // Insertar Eventos
        $eventos = [
            [
                'id_evento' => 1,
                'id_color' => 1,
                'nombre_evento' => 'AÑO NUEVO',
                'tipo_evento' => '3',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 2,
                'id_color' => 2,
                'nombre_evento' => 'DÍA DE LA DEMOCRACIA',
                'tipo_evento' => '3',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 3,
                'id_color' => 3,
                'nombre_evento' => 'CARNAVAL',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 4,
                'id_color' => 4,
                'nombre_evento' => 'DÍA DE TURÉN - NO LABORABLE SOLO NÚCLEO ACADEMICO TUREN ',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 5,
                'id_color' => 5,
                'nombre_evento' => 'JUEVES y VIERNES SANTO',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 6,
                'id_color' => 6,
                'nombre_evento' => 'DECLARACION DE LA INDEPENDENCIA',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 7,
                'id_color' => 7,
                'nombre_evento' => 'DÍA DEL TRABAJADOR',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 8,
                'id_color' => 8,
                'nombre_evento' => 'DÍA DE SAN ISIDRO EL LABRADOR - NO LABORABLE SOLO EN NÚCLEO ACADÉMICO TUREN',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 9,
                'id_color' => 9,
                'nombre_evento' => 'DÍA DE JACINTO LARA - NO LABORABLE SOLO EN EL CONVENIO CON LA COMUNA SOCIALISTA EL MAIZAL',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 10,
                'id_color' => 10,
                'nombre_evento' => 'DÍA DE PÁEZ - NO LABORABLE SOLO PARA LA SEDE PRINCIPAL',
                'tipo_evento' => '1',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 11,
                'id_color' => 11,
                'nombre_evento' => 'Corrección de notas',
                'tipo_evento' => '2',
                'is_laborable_evento' => 1,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 12,
                'id_color' => 12,
                'nombre_evento' => 'Batalla de Carabobo',
                'tipo_evento' => '1',
                'is_laborable_evento' => 0,
                'is_repetible_evento' => 0,
                'estatus' => '1'
            ],
            [
                'id_evento' => 13,
                'id_color' => 14,
                'nombre_evento' => 'NATALICIO DEL LIBERTADOR SIMÓN BOLÍVAR',
                'tipo_evento' => '2',
                'is_laborable_evento' => 0,
                'is_repetible_evento' => 1,
                'estatus' => '1'
            ],
        ];

        DB::table('evento')->insert($eventos);
    }
}

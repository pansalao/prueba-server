<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement("CREATE TABLE IF NOT EXISTS emulacion_sogac_2.sede (sed_codigo INT AUTO_INCREMENT PRIMARY KEY, sed_nombre VARCHAR(255), sed_estatus VARCHAR(2))");

$count = DB::table('emulacion_sogac_2.sede')->count();
if ($count == 0) {
    DB::table('emulacion_sogac_2.sede')->insert([
        ['sed_nombre' => 'Sede Principal', 'sed_estatus' => '1'],
        ['sed_nombre' => 'Sede Alterna', 'sed_estatus' => '1']
    ]);
    echo "Sedes creadas.\n";
} else {
    echo "Sedes ya existen.\n";
}

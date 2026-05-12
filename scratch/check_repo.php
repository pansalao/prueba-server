<?php

use App\Models\Evento;
use App\Repositories\Evento\EventoIndexRepo;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$repo = new EventoIndexRepo();
$eventos = $repo->listar();

echo "Count: " . $eventos->count() . "\n";
if ($eventos->count() > 0) {
    $first = $eventos->first();
    echo "Class: " . get_class($first) . "\n";
    echo "Type Name: " . ($first->tipo_evento_nombre ?? 'NOT FOUND') . "\n";
}

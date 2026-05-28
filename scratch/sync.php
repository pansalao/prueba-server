<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$component = new \App\Livewire\Permiso\ListPermiso();
$component->boot();
$component->render();

echo "Permisos sincronizados exitosamente en la base de datos.\n";

<?php
$file = 'c:\Users\Aleja\Desktop\sistemas y analisis\SAUPA\Proyecto-Planificacion\resources\views\livewire\pages\calendario\editar-calendario.blade.php';
$lines = file($file);
for ($i = 410; $i <= 425; $i++) {
    if (isset($lines[$i - 1])) {
        $line = $lines[$i - 1];
        echo "Line $i: " . json_encode($line) . "\n";
    }
}

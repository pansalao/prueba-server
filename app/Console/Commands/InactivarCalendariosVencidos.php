<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CalendarioAcademico;

class InactivarCalendariosVencidos extends Command
{
    /**
     * Nombre y firma del comando de consola.
     */
    protected $signature = 'calendario:inactivar-vencidos';

    /**
     * Descripción del comando de consola.
     */
    protected $description = 'Inactiva automáticamente los calendarios académicos cuya fecha de fin ya pasó';

    /**
     * Ejecutar el comando.
     */
    public function handle()
    {
        $cantidad = CalendarioAcademico::inactivarVencidos();

        if ($cantidad > 0) {
            $this->info("Se inactivaron {$cantidad} calendario(s) vencido(s).");
        } else {
            $this->info('No hay calendarios vencidos para inactivar.');
        }

        return Command::SUCCESS;
    }
}

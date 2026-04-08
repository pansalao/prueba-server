<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        // Inactivar calendarios vencidos todos los días a las 00:01
        $schedule->command('calendario:inactivar-vencidos')->dailyAt('00:01');
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(
            [
                'role' => \App\Http\Middleware\RoleMiddleware::class,
                'log.activity' => \App\Http\Middleware\LogActivity::class,
                // 'profesor' => \App\Http\Middleware\ProfesorMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

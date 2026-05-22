<?php

namespace App\Support;

use App\Models\Evento;
use Carbon\Carbon;

/**
 * Cálculo de semanas de lapso alineado con la cuadrícula (semanas lun–dom).
 * Las semanas con eventos especiales 4 (Semana Santa) o 5 (Carnaval) no cuentan.
 */
class CalendarioLapsoSemanas
{
    /**
     * Lunes de la semana que contiene la fecha (misma lógica que la cuadrícula JS).
     */
    public static function lunesDeSemana(string $fecha): Carbon
    {
        return Carbon::parse($fecha)->startOfWeek(Carbon::MONDAY);
    }

    /**
     * @param  array<int, array<string, mixed>>  $eventosRegistrados
     * @return array<int, true>  id_evento => true
     */
    public static function idsEventosFestivos(array $eventosRegistrados): array
    {
        $ids = collect($eventosRegistrados)
            ->pluck('id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $festivos = [];

        if (!empty($ids)) {
            foreach (Evento::whereIn('id_evento', $ids)->get(['id_evento', 'especial_evento', 'nombre_evento']) as $evento) {
                if (self::eventoModeloEsFestivo($evento)) {
                    $festivos[(int) $evento->id_evento] = true;
                }
            }
        }

        return $festivos;
    }

    public static function eventoModeloEsFestivo(Evento $evento): bool
    {
        $esp = (string) ($evento->especial_evento ?? '');
        if (in_array($esp, ['4', '5'], true)) {
            return true;
        }

        return self::nombreIndicaSemanaFestiva((string) $evento->nombre_evento);
    }

    /**
     * @param  array<string, mixed>  $ev
     * @param  array<int, true>  $idsFestivos
     */
    public static function registroEsFestivo(array $ev, array $idsFestivos): bool
    {
        $id = (int) ($ev['id'] ?? 0);
        if ($id && isset($idsFestivos[$id])) {
            return true;
        }

        $esp = (string) ($ev['especial_evento'] ?? '');
        if (in_array($esp, ['4', '5'], true)) {
            return true;
        }

        $nombre = (string) ($ev['nombre_evento'] ?? $ev['nombre'] ?? '');

        return self::nombreIndicaSemanaFestiva($nombre);
    }

    public static function nombreIndicaSemanaFestiva(string $nombre): bool
    {
        $nombre = mb_strtolower($nombre);

        return str_contains($nombre, 'semana santa')
            || str_contains($nombre, 'carnaval')
            || str_contains($nombre, 'viernes santo')
            || str_contains($nombre, 'jueves santo');
    }

    /**
     * Lunes (Y-m-d) de cada semana festiva según eventos ya asignados al calendario.
     *
     * @param  array<int, array<string, mixed>>  $eventosRegistrados
     * @return array<string, true>
     */
    public static function lunesSemanasFestivas(array $eventosRegistrados): array
    {
        $festivas = [];
        $idsFestivos = self::idsEventosFestivos($eventosRegistrados);

        foreach ($eventosRegistrados as $ev) {
            if (!self::registroEsFestivo($ev, $idsFestivos)) {
                continue;
            }

            $inicio = $ev['inicio'] ?? null;
            $fin = $ev['fin'] ?? $inicio;
            if (!$inicio) {
                continue;
            }

            $lunes = self::lunesDeSemana($inicio);
            $lunesFin = self::lunesDeSemana($fin);

            while ($lunes->lte($lunesFin)) {
                $festivas[$lunes->format('Y-m-d')] = true;
                $lunes->addWeek();
            }
        }

        return $festivas;
    }

    /**
     * Viernes de la semana N académica (omite semanas festivas 4/5).
     *
     * @param  array<int, array<string, mixed>>  $eventosRegistrados
     */
    public static function fechaFinLapso(string $inicio, int $semanas, array $eventosRegistrados = []): string
    {
        if ($semanas < 1) {
            return $inicio;
        }

        $festivas = self::lunesSemanasFestivas($eventosRegistrados);
        $lunes = self::lunesDeSemana($inicio);
        $semanasContadas = 0;
        $maxIteraciones = $semanas + count($festivas) + 104;

        for ($i = 0; $i < $maxIteraciones; $i++) {
            $lunesStr = $lunes->format('Y-m-d');

            if (!isset($festivas[$lunesStr])) {
                $semanasContadas++;
                if ($semanasContadas >= $semanas) {
                    return $lunes->copy()->addDays(4)->format('Y-m-d');
                }
            }

            $lunes->addWeek();
        }

        return self::lunesDeSemana($inicio)
            ->addDays(($semanas * 7) - 3)
            ->format('Y-m-d');
    }

    /**
     * Semanas académicas entre inicio y fin (omite semanas festivas 4/5).
     *
     * @param  array<int, array<string, mixed>>  $eventosRegistrados
     */
    public static function contarSemanas(string $inicio, string $fin, array $eventosRegistrados = []): int
    {
        $festivas = self::lunesSemanasFestivas($eventosRegistrados);
        $lunesInicio = self::lunesDeSemana($inicio);
        $lunesFin = self::lunesDeSemana($fin);

        if ($lunesFin->lt($lunesInicio)) {
            return 0;
        }

        $count = 0;
        $lunes = $lunesInicio->copy();

        while ($lunes->lte($lunesFin)) {
            if (!isset($festivas[$lunes->format('Y-m-d')])) {
                $count++;
            }
            $lunes->addWeek();
        }

        return $count;
    }
}

<?php

namespace App\Support;

/**
 * Valores en letras para cuentas de cobro colombianas:
 *   1.500.000   → "UN MILLÓN QUINIENTOS MIL PESOS M/CTE"
 *   2.000.000   → "DOS MILLONES DE PESOS M/CTE"
 *   21.000      → "VEINTIÚN MIL PESOS M/CTE"
 */
class NumberToWords
{
    private const UNITS = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
        'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve',
        'veinte', 'veintiuno', 'veintidós', 'veintitrés', 'veinticuatro', 'veinticinco', 'veintiséis', 'veintisiete',
        'veintiocho', 'veintinueve'];

    private const TENS = [3 => 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];

    private const HUNDREDS = [1 => 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos',
        'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

    public static function pesos(float|int|string $amount): string
    {
        $amount = round((float) $amount, 2);
        $integer = (int) floor($amount);
        $cents = (int) round(($amount - $integer) * 100);

        $text = match (true) {
            $integer === 0 => 'cero pesos',
            $integer === 1 => 'un peso',
            $integer % 1_000_000 === 0 => self::words($integer).' de pesos', // "un millón DE pesos"
            default => self::words($integer).' pesos',
        };

        if ($cents > 0) {
            $text .= ' con '.self::words($cents).' centavos';
        }

        return mb_strtoupper($text.' m/cte');
    }

    /** Número entero en palabras, con apócope final ("veintiún", "un") porque va antes de un sustantivo. */
    public static function words(int $n): string
    {
        if ($n === 0) {
            return 'cero';
        }

        $parts = [];
        $millions = intdiv($n, 1_000_000);
        $rest = $n % 1_000_000;

        if ($millions > 0) {
            $parts[] = $millions === 1 ? 'un millón' : self::words($millions).' millones';
        }

        $thousands = intdiv($rest, 1000);
        $units = $rest % 1000;

        if ($thousands > 0) {
            $parts[] = $thousands === 1 ? 'mil' : self::hundreds($thousands).' mil';
        }
        if ($units > 0) {
            $parts[] = self::hundreds($units);
        }

        return implode(' ', $parts);
    }

    private static function hundreds(int $n): string
    {
        if ($n === 100) {
            return 'cien';
        }

        $h = intdiv($n, 100);
        $r = $n % 100;
        $text = $h > 0 ? self::HUNDREDS[$h] : '';

        if ($r > 0) {
            $tens = $r < 30
                ? self::UNITS[$r]
                : self::TENS[intdiv($r, 10)].($r % 10 ? ' y '.self::UNITS[$r % 10] : '');
            $text = trim($text.' '.$tens);
        }

        // Apócope: "uno" → "un", "veintiuno" → "veintiún" (veintiún mil, treinta y un pesos)
        return preg_replace(['/veintiuno$/', '/uno$/'], ['veintiún', 'un'], $text);
    }
}

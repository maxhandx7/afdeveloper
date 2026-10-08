<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Currency: string implements HasLabel
{
    case COP = 'COP';
    case USD = 'USD';
    case EUR = 'EUR';

    public function getLabel(): string
    {
        return match ($this) {
            self::COP => 'COP — Peso colombiano',
            self::USD => 'USD — Dólar',
            self::EUR => 'EUR — Euro',
        };
    }

    public static function base(): self
    {
        return self::from(config('afdeveloper.base_currency', 'COP'));
    }
}

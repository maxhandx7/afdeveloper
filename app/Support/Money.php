<?php

namespace App\Support;

use App\Enums\Currency;
use Illuminate\Support\Number;

class Money
{
    public static function format(float|int|string|null $amount, Currency|string|null $currency = null): string
    {
        $currency = $currency instanceof Currency ? $currency : Currency::tryFrom((string) $currency) ?? Currency::base();

        return Number::currency(
            (float) $amount,
            in: $currency->value,
            locale: 'es_CO',
            precision: $currency === Currency::COP ? 0 : 2,
        );
    }

    /** Normaliza el valor que llega de un formulario (enum o string) a enum. */
    public static function currency(mixed $value): Currency
    {
        return $value instanceof Currency ? $value : (Currency::tryFrom((string) $value) ?? Currency::base());
    }
}

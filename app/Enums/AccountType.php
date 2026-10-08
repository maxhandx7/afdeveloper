<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AccountType: string implements HasLabel
{
    case Bank = 'bank';
    case Wallet = 'wallet';
    case Cash = 'cash';
    case Card = 'card';

    public function getLabel(): string
    {
        return match ($this) {
            self::Bank => 'Cuenta bancaria',
            self::Wallet => 'Billetera digital (Nequi, Daviplata…)',
            self::Cash => 'Efectivo',
            self::Card => 'Tarjeta de crédito',
        };
    }
}

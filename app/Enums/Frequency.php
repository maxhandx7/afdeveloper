<?php

namespace App\Enums;

use Carbon\CarbonInterface;
use Filament\Support\Contracts\HasLabel;

enum Frequency: string implements HasLabel
{
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';

    public function getLabel(): string
    {
        return match ($this) {
            self::Weekly => 'Semanal',
            self::Monthly => 'Mensual',
            self::Quarterly => 'Trimestral',
            self::Yearly => 'Anual',
        };
    }

    public function advance(CarbonInterface $date): CarbonInterface
    {
        // NoOverflow: un cobro del 31 pasa al 30 (o 28) en vez de saltar de mes.
        return match ($this) {
            self::Weekly => $date->copy()->addWeek(),
            self::Monthly => $date->copy()->addMonthNoOverflow(),
            self::Quarterly => $date->copy()->addMonthsNoOverflow(3),
            self::Yearly => $date->copy()->addYearNoOverflow(),
        };
    }
}

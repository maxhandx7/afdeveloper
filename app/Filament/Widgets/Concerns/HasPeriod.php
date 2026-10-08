<?php

namespace App\Filament\Widgets\Concerns;

use Carbon\CarbonImmutable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

/** Traduce el filtro "periodo" del dashboard a rangos de fechas. */
trait HasPeriod
{
    use InteractsWithPageFilters;

    /** @return array{0: CarbonImmutable, 1: CarbonImmutable} */
    protected function period(): array
    {
        $now = CarbonImmutable::now();

        return match ($this->pageFilters['period'] ?? 'this_month') {
            'last_month' => [$now->subMonthNoOverflow()->startOfMonth(), $now->subMonthNoOverflow()->endOfMonth()],
            'this_quarter' => [$now->startOfQuarter(), $now->endOfQuarter()],
            'this_year' => [$now->startOfYear(), $now->endOfYear()],
            'last_12' => [$now->subMonths(11)->startOfMonth(), $now->endOfMonth()],
            default => [$now->startOfMonth(), $now->endOfMonth()],
        };
    }

    /** El periodo inmediatamente anterior, de la misma duración, para comparar. */
    protected function previousPeriod(): array
    {
        [$from, $to] = $this->period();
        $months = (int) round($from->diffInMonths($to->addDay()));

        return [$from->subMonthsNoOverflow($months), $from->subDay()];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Widgets\Concerns\HasPeriod;
use App\Models\Transaction;
use App\Support\Money;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FinanceOverview extends StatsOverviewWidget
{
    use HasPeriod;

    protected static ?int $sort = 1;

    protected int|array|null $columns = ['md' => 2, 'xl' => 4];

    protected function getStats(): array
    {
        [$from, $to] = $this->period();
        [$prevFrom, $prevTo] = $this->previousPeriod();

        $income = (float) Transaction::income()->paid()->between($from, $to)->sum('amount_base');
        $expense = (float) Transaction::expense()->paid()->between($from, $to)->sum('amount_base');
        $prevIncome = (float) Transaction::income()->paid()->between($prevFrom, $prevTo)->sum('amount_base');
        $prevExpense = (float) Transaction::expense()->paid()->between($prevFrom, $prevTo)->sum('amount_base');

        $receivable = Transaction::income()->pending();
        $overdue = (clone $receivable)->whereDate('due_date', '<', today())->count();
        $payable = (float) Transaction::expense()->pending()->sum('amount_base');

        return [
            $this->trendStat('Ingresos', $income, $prevIncome, higherIsBetter: true)
                ->chart($this->sparkline('income')),

            $this->trendStat('Gastos', $expense, $prevExpense, higherIsBetter: false)
                ->chart($this->sparkline('expense')),

            Stat::make('Utilidad', Money::format($income - $expense))
                ->description($income > 0
                    ? 'Margen del '.round((($income - $expense) / $income) * 100).'%'
                    : 'Sin ingresos en el periodo')
                ->color($income - $expense >= 0 ? 'success' : 'danger'),

            Stat::make('Por cobrar', Money::format((float) $receivable->sum('amount_base')))
                ->description($overdue > 0
                    ? "{$overdue} vencido(s) · por pagar ".Money::format($payable)
                    : 'Por pagar '.Money::format($payable))
                ->descriptionIcon($overdue > 0 ? Heroicon::OutlinedExclamationTriangle : null)
                ->color($overdue > 0 ? 'danger' : 'warning')
                ->url(TransactionResource::getUrl('index', ['tab' => 'pending'])),
        ];
    }

    private function trendStat(string $label, float $current, float $previous, bool $higherIsBetter): Stat
    {
        $stat = Stat::make($label, Money::format($current));

        if ($previous <= 0) {
            return $stat->description('Sin datos del periodo anterior');
        }

        $change = (($current - $previous) / $previous) * 100;
        $good = $higherIsBetter ? $change >= 0 : $change <= 0;

        return $stat
            ->description(($change >= 0 ? '+' : '').round($change).'% vs periodo anterior')
            ->descriptionIcon($change >= 0 ? Heroicon::OutlinedArrowTrendingUp : Heroicon::OutlinedArrowTrendingDown)
            ->color($good ? 'success' : 'danger');
    }

    /** Mini-gráfica de los últimos 6 meses. */
    private function sparkline(string $type): array
    {
        return collect(range(5, 0))->map(function (int $ago) use ($type) {
            $month = now()->subMonthsNoOverflow($ago);

            return (float) Transaction::query()->where('type', $type)->paid()
                ->between($month->copy()->startOfMonth(), $month->copy()->endOfMonth())
                ->sum('amount_base');
        })->all();
    }
}

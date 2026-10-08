<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class CashFlowChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Flujo de caja — últimos 12 meses';

    protected int|string|array $columnSpan = ['md' => 2, 'xl' => 2];

    protected ?string $maxHeight = '300px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $from = now()->subMonths(11)->startOfMonth();

        // Una sola consulta agrupada en PHP (funciona igual en MySQL y SQLite).
        $rows = Transaction::paid()
            ->whereDate('date', '>=', $from->toDateString())
            ->get(['type', 'date', 'amount_base'])
            ->groupBy(fn (Transaction $t) => $t->date->format('Y-m').'|'.$t->type->value)
            ->map(fn ($group) => (float) $group->sum('amount_base'));

        $months = collect(range(0, 11))->map(fn (int $i) => $from->copy()->addMonthsNoOverflow($i));

        $series = fn (TransactionType $type) => $months
            ->map(fn ($m) => $rows->get($m->format('Y-m').'|'.$type->value, 0))->all();

        $income = $series(TransactionType::Income);
        $expense = $series(TransactionType::Expense);

        return [
            'datasets' => [
                ['label' => 'Ingresos', 'data' => $income, 'backgroundColor' => '#10b981', 'borderRadius' => 4],
                ['label' => 'Gastos', 'data' => $expense, 'backgroundColor' => '#f43f5e', 'borderRadius' => 4],
                [
                    'label' => 'Utilidad',
                    'type' => 'line',
                    'data' => array_map(fn ($a, $b) => $a - $b, $income, $expense),
                    'borderColor' => '#5a6478',
                    'backgroundColor' => '#5a6478',
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months->map(fn ($m) => ucfirst($m->translatedFormat('M y')))->all(),
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
            {
                scales: { y: { ticks: { callback: (v) => '$' + Intl.NumberFormat('es-CO', { notation: 'compact' }).format(v) } } },
                plugins: { tooltip: { callbacks: { label: (c) => c.dataset.label + ': $' + Intl.NumberFormat('es-CO').format(c.parsed.y) } } },
            }
        JS);
    }
}

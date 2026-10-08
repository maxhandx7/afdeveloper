<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasPeriod;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class ExpensesByCategoryChart extends ChartWidget
{
    use HasPeriod;

    protected static ?int $sort = 3;

    protected ?string $heading = '¿En qué se va la plata?';

    protected ?string $maxHeight = '300px';

    private const FALLBACK = ['#5a6478', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#84cc16', '#f97316'];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        [$from, $to] = $this->period();

        $data = Transaction::expense()->paid()->between($from, $to)
            ->with('category:id,name,color')
            ->get(['category_id', 'amount_base'])
            ->groupBy(fn (Transaction $t) => $t->category_id ?? 0)
            ->map(fn ($group) => [
                'label' => $group->first()->category?->name ?? 'Sin categoría',
                'color' => $group->first()->category?->color,
                'total' => (float) $group->sum('amount_base'),
            ])
            ->sortByDesc('total')
            ->values();

        return [
            'datasets' => [[
                'data' => $data->pluck('total')->all(),
                'backgroundColor' => $data->map(fn ($row, $i) => $row['color'] ?: self::FALLBACK[$i % count(self::FALLBACK)])->all(),
                'borderWidth' => 0,
            ]],
            'labels' => $data->pluck('label')->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom']],
            'scales' => ['x' => ['display' => false], 'y' => ['display' => false]],
            'cutout' => '65%',
        ];
    }
}

<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $title = 'Resumen';

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->label('Periodo')
                ->options([
                    'this_month' => 'Este mes',
                    'last_month' => 'Mes pasado',
                    'this_quarter' => 'Este trimestre',
                    'this_year' => 'Este año',
                    'last_12' => 'Últimos 12 meses',
                ])
                ->default('this_month')
                ->selectablePlaceholder(false),
        ]);
    }

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 3];
    }
}

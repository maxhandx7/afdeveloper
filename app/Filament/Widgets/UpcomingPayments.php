<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use App\Support\Money;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingPayments extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pendientes por cobrar y por pagar';

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::pending()->with('client')->orderByRaw('due_date is null')->orderBy('due_date'))
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->columns([
                TextColumn::make('type')->label('')->badge(),
                TextColumn::make('description')->label('Concepto')
                    ->description(fn (Transaction $r) => $r->client?->name),
                TextColumn::make('due_date')->label('Vence')->date('d M Y')->placeholder('Sin fecha')
                    ->color(fn (Transaction $r) => $r->isOverdue() ? 'danger' : null)
                    ->description(fn (Transaction $r) => $r->due_date?->diffForHumans()),
                TextColumn::make('amount_base')->label('Monto')->alignEnd()
                    ->formatStateUsing(fn ($state) => Money::format($state)),
            ])
            ->recordActions([TransactionsTable::markAsPaidAction()])
            ->emptyStateHeading('Todo al día 🎉')
            ->emptyStateDescription('No hay cobros ni pagos pendientes.');
    }
}

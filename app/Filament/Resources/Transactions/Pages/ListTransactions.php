<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Exportar CSV')
                ->icon(Heroicon::OutlinedArrowDownTray)
                ->color('gray')
                ->tooltip('Exporta lo que estás viendo, con los filtros aplicados')
                ->action(fn () => $this->exportCsv()),
            CreateAction::make()->label('Nuevo movimiento'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Todos'),
            'income' => Tab::make('Ingresos')
                ->icon(TransactionType::Income->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', TransactionType::Income)),
            'expense' => Tab::make('Gastos')
                ->icon(TransactionType::Expense->getIcon())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', TransactionType::Expense)),
            'pending' => Tab::make('Pendientes')
                ->badge(Transaction::pending()->count() ?: null)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', TransactionStatus::Pending)),
        ];
    }

    protected function exportCsv(): StreamedResponse
    {
        $query = $this->getFilteredSortedTableQuery()->with(['category', 'account', 'client', 'project']);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM: Excel abre bien las tildes.

            fputcsv($out, ['Fecha', 'Tipo', 'Descripción', 'Categoría', 'Cliente', 'Proyecto', 'Cuenta',
                'Monto', 'Moneda', 'Tasa', 'Monto base', 'Estado', 'Vence', 'Pagado el', 'Referencia'], ';');

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $t) {
                    fputcsv($out, [
                        $t->date?->format('Y-m-d'), $t->type->getLabel(), $t->description,
                        $t->category?->name, $t->client?->name, $t->project?->title, $t->account?->name,
                        $t->amount, $t->currency->value, $t->exchange_rate, $t->amount_base,
                        $t->status->getLabel(), $t->due_date?->format('Y-m-d'), $t->paid_at?->format('Y-m-d'),
                        $t->reference,
                    ], ';');
                }
            });

            fclose($out);
        }, 'movimientos-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

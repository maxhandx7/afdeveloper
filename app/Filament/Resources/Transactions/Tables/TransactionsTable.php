<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Enums\Currency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Support\Money;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->striped()
            ->columns([
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->wrap()
                    ->description(fn (Transaction $record) => collect([
                        $record->category?->name,
                        $record->client?->name,
                    ])->filter()->implode(' · ')),

                TextColumn::make('amount_base')
                    ->label('Monto')
                    ->formatStateUsing(fn (Transaction $record) => ($record->type === TransactionType::Expense ? '− ' : '+ ')
                        .Money::format($record->amount_base))
                    ->color(fn (Transaction $record) => $record->type->getColor())
                    ->description(fn (Transaction $record) => $record->currency !== Currency::base()
                        ? Money::format($record->amount, $record->currency).' × '.number_format((float) $record->exchange_rate, 2, ',', '.')
                        : null)
                    ->alignEnd()
                    ->sortable()
                    ->summarize([
                        Sum::make()
                            ->label('Ingresos')
                            ->query(fn ($query) => $query->where('type', TransactionType::Income->value))
                            ->formatStateUsing(fn ($state) => Money::format($state)),
                        Sum::make()
                            ->label('Gastos')
                            ->query(fn ($query) => $query->where('type', TransactionType::Expense->value))
                            ->formatStateUsing(fn ($state) => Money::format($state)),
                    ]),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->description(fn (Transaction $record) => $record->isOverdue()
                        ? 'Vencido '.$record->due_date->diffForHumans()
                        : ($record->status === TransactionStatus::Pending && $record->due_date
                            ? 'Vence '.$record->due_date->translatedFormat('d M')
                            : null)),

                TextColumn::make('account.name')
                    ->label('Cuenta')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('project.title')
                    ->label('Proyecto')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('reference')
                    ->label('Referencia')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->label('Tipo')->options(TransactionType::class),
                SelectFilter::make('status')->label('Estado')->options(TransactionStatus::class),
                SelectFilter::make('category')->label('Categoría')->relationship('category', 'name')->preload()->multiple(),
                SelectFilter::make('client')->label('Cliente')->relationship('client', 'name')->searchable()->preload(),
                SelectFilter::make('account')->label('Cuenta')->relationship('account', 'name')->preload(),
                Filter::make('period')
                    ->label('Periodo')
                    ->schema([
                        DatePicker::make('from')->label('Desde')->native(false),
                        DatePicker::make('until')->label('Hasta')->native(false),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('date', '<=', $date)))
                    ->indicateUsing(fn (array $data) => array_filter([
                        ($data['from'] ?? null) ? 'Desde '.$data['from'] : null,
                        ($data['until'] ?? null) ? 'Hasta '.$data['until'] : null,
                    ])),
                TrashedFilter::make(),
            ])
            ->recordActions([
                self::markAsPaidAction(),
                EditAction::make()->iconButton(),
                ActionGroup::make([
                    Action::make('attachment')
                        ->label('Ver soporte')
                        ->icon(Heroicon::OutlinedDocumentText)
                        ->visible(fn (Transaction $record) => filled($record->attachment))
                        ->url(fn (Transaction $record) => Storage::disk('local')->temporaryUrl($record->attachment, now()->addMinutes(10)))
                        ->openUrlInNewTab(),
                    ReplicateAction::make()
                        ->label('Duplicar')
                        ->excludeAttributes(['attachment', 'paid_at', 'amount_base'])
                        ->mutateRecordDataUsing(fn (array $data) => [...$data, 'date' => today()]),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsPaid')
                        ->label('Marcar como pagados')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->filter(fn (Transaction $t) => $t->status === TransactionStatus::Pending)
                                ->each(fn (Transaction $t) => $t->markAsPaid());
                        })
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Todavía no hay movimientos')
            ->emptyStateDescription('Registra tu primer ingreso o gasto con el botón de arriba.');
    }

    public static function markAsPaidAction(): Action
    {
        return Action::make('markAsPaid')
            ->label('Pagado')
            ->tooltip('Marcar como pagado')
            ->icon(Heroicon::OutlinedCheckCircle)
            ->iconButton()
            ->color('success')
            ->visible(fn (Transaction $record) => $record->status === TransactionStatus::Pending && ! $record->trashed())
            ->modalHeading(fn (Transaction $record) => "Registrar pago: {$record->description}")
            ->modalWidth('md')
            ->schema(fn (Transaction $record) => array_filter([
                DatePicker::make('paid_at')->label('Fecha de pago')->default(today())->native(false)->required(),
                $record->currency !== Currency::base()
                    ? TextInput::make('exchange_rate')
                        ->label('Tasa de cambio a '.Currency::base()->value)
                        ->helperText('La tasa real a la que te liquidaron el pago.')
                        ->numeric()->minValue(0.0001)->default($record->exchange_rate)->required()
                    : null,
            ]))
            ->action(function (Transaction $record, array $data) {
                if (isset($data['exchange_rate'])) {
                    $record->exchange_rate = $data['exchange_rate'];
                }

                $record->markAsPaid(\Illuminate\Support\Carbon::parse($data['paid_at']));

                Notification::make()->success()->title('Pago registrado')->send();
            });
    }
}

<?php

namespace App\Filament\Support\Billing;

use App\Enums\BillingDocumentStatus;
use App\Enums\BillingDocumentType;
use App\Models\BillingDocument;
use App\Support\Money;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BillingDocumentTable
{
    public static function configure(Table $table, BillingDocumentType $type): Table
    {
        return $table
            ->defaultSort('sequence', 'desc')
            ->columns([
                TextColumn::make('number')->label('No.')->searchable()->sortable(query: fn ($query, $direction) => $query->orderBy('sequence', $direction))->weight('bold'),
                TextColumn::make('client.name')->label('Cliente')->searchable(['name', 'company'])
                    ->description(fn (BillingDocument $record) => $record->client?->company),
                TextColumn::make('issue_date')->label('Fecha')->date('d M Y')->sortable(),
                TextColumn::make('due_date')->label($type === BillingDocumentType::Quote ? 'Válida hasta' : 'Vence')->date('d M Y')
                    ->color(fn (BillingDocument $record) => $record->status === BillingDocumentStatus::Sent && $record->due_date?->isPast() ? 'danger' : null),
                TextColumn::make('total')->label('Total')->alignEnd()->sortable()
                    ->formatStateUsing(fn ($state) => Money::format($state)),
                TextColumn::make('status')->label('Estado')->badge(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Estado')
                    ->options(collect($type->statuses())->mapWithKeys(fn ($s) => [$s->value => $s->getLabel()])),
                SelectFilter::make('client')->label('Cliente')->relationship('client', 'name')->searchable()->preload(),
            ])
            ->recordActions([
                BillingDocumentActions::pdf()->iconButton(),
                BillingDocumentActions::send()->iconButton(),
                EditAction::make()->iconButton(),
                ActionGroup::make([
                    BillingDocumentActions::markAsPaid(),
                    BillingDocumentActions::convert(),
                    BillingDocumentActions::duplicate(),
                    BillingDocumentActions::close(),
                    DeleteAction::make()->visible(fn (BillingDocument $record) => $record->status === BillingDocumentStatus::Draft),
                ]),
            ])
            ->emptyStateHeading($type === BillingDocumentType::Quote ? 'Sin cotizaciones' : 'Sin cuentas de cobro')
            ->emptyStateDescription('Crea la primera con el botón de arriba.');
    }
}

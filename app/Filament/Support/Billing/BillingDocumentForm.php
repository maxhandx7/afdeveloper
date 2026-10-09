<?php

namespace App\Filament\Support\Billing;

use App\Enums\BillingDocumentType;
use App\Support\Money;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class BillingDocumentForm
{
    public static function configure(Schema $schema, BillingDocumentType $type): Schema
    {
        $isQuote = $type === BillingDocumentType::Quote;

        return $schema->columns(1)->components([
            Section::make()->columns(3)->schema([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->company ? "{$record->company} — {$record->name}" : $record->name)
                    ->searchable(['name', 'company', 'document'])
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')->label('Nombre de contacto')->required(),
                        TextInput::make('company')->label('Empresa'),
                        TextInput::make('document')->label('NIT / Cédula'),
                        TextInput::make('city')->label('Ciudad'),
                        TextInput::make('email')->label('Correo')->email(),
                        TextInput::make('phone')->label('WhatsApp / teléfono')->tel(),
                    ]),
                DatePicker::make('issue_date')->label('Fecha')->default(today())->native(false)->displayFormat('d M Y')->required(),
                DatePicker::make('due_date')
                    ->label($isQuote ? 'Válida hasta' : 'Vence el')
                    ->default(today()->addDays($isQuote ? 15 : 15))
                    ->native(false)->displayFormat('d M Y'),
            ]),

            Section::make('Conceptos')->schema([
                Repeater::make('items')
                    ->hiddenLabel()
                    ->columns(12)
                    ->defaultItems(1)
                    ->minItems(1)
                    ->reorderable()
                    ->addActionLabel('Agregar concepto')
                    ->live(onBlur: true)
                    ->schema([
                        Textarea::make('description')->label('Descripción')->rows(2)->required()->columnSpan(['default' => 12, 'md' => 7]),
                        TextInput::make('quantity')->label('Cantidad')->numeric()->default(1)->minValue(0.01)->required()->columnSpan(['default' => 4, 'md' => 2]),
                        TextInput::make('unit_price')->label('Valor unitario')->numeric()->prefix('$')->minValue(0)->required()->columnSpan(['default' => 8, 'md' => 3]),
                    ]),

                Text::make(fn (Get $get) => 'Total: '.Money::format(
                    collect($get('items') ?? [])->sum(fn ($i) => (float) ($i['quantity'] ?? 0) * (float) ($i['unit_price'] ?? 0))
                )),
            ]),

            Section::make($isQuote ? 'Observaciones y condiciones' : 'Observaciones')->schema([
                Textarea::make('notes')->hiddenLabel()->rows(3)
                    ->placeholder($isQuote
                        ? "Tiempo estimado: 4 semanas.\nForma de pago: 50% al iniciar y 50% contra entrega."
                        : 'Corresponde a los trabajos de …'),
            ]),
        ]);
    }
}

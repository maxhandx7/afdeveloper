<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\Currency;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Category;
use App\Support\Money;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Grid::make(1)
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Movimiento')
                            ->columns(2)
                            ->schema([
                                ToggleButtons::make('type')
                                    ->label('Tipo')
                                    ->options(TransactionType::class)
                                    ->default(TransactionType::Income)
                                    ->inline()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('category_id', null))
                                    ->columnSpanFull(),

                                TextInput::make('description')
                                    ->label('Descripción')
                                    ->placeholder('Ej: Desarrollo módulo de inventario — Big Group')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('amount')
                                    ->label('Monto')
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->prefix('$')
                                    ->required(),

                                Select::make('currency')
                                    ->label('Moneda')
                                    ->options(Currency::class)
                                    ->default(Currency::base())
                                    ->required()
                                    ->live(),

                                TextInput::make('exchange_rate')
                                    ->label('Tasa de cambio a '.Currency::base()->value)
                                    ->helperText('¿Cuántos pesos te dieron por cada unidad? Ej: 4150')
                                    ->numeric()
                                    ->minValue(0.0001)
                                    ->default(1)
                                    ->required()
                                    ->visible(fn (Get $get) => Money::currency($get('currency')) !== Currency::base()),

                                DatePicker::make('date')
                                    ->label('Fecha')
                                    ->default(today())
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->required(),
                            ]),

                        Section::make('Clasificación')
                            ->columns(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Categoría')
                                    ->relationship(
                                        'category',
                                        'name',
                                        fn (Builder $query, Get $get) => $query->where('type', self::type($get('type'))),
                                    )
                                    ->preload()
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')->label('Nombre')->required()->maxLength(80),
                                        ColorPicker::make('color')->label('Color'),
                                    ])
                                    ->createOptionUsing(fn (array $data, Get $get) => Category::create([
                                        ...$data,
                                        'type' => self::type($get('type')),
                                    ])->getKey()),

                                Select::make('finance_account_id')
                                    ->label('Cuenta')
                                    ->relationship('account', 'name', fn (Builder $query) => $query->where('is_active', true))
                                    ->preload(),

                                Select::make('client_id')
                                    ->label('Cliente')
                                    ->relationship('client', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->label('Nombre')->required(),
                                        TextInput::make('company')->label('Empresa'),
                                        TextInput::make('email')->label('Correo')->email(),
                                        TextInput::make('phone')->label('Teléfono')->tel(),
                                    ]),

                                Select::make('project_id')
                                    ->label('Proyecto')
                                    ->relationship('project', 'title')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),

                Grid::make(1)
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Estado')
                            ->schema([
                                ToggleButtons::make('status')
                                    ->label('¿Ya se pagó?')
                                    ->options(TransactionStatus::class)
                                    ->default(TransactionStatus::Paid)
                                    ->inline()
                                    ->required()
                                    ->live(),

                                DatePicker::make('due_date')
                                    ->label('Fecha límite de pago')
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->visible(fn (Get $get) => self::status($get('status')) === TransactionStatus::Pending),

                                DatePicker::make('paid_at')
                                    ->label('Pagado el')
                                    ->helperText('Si lo dejas vacío se usa la fecha del movimiento.')
                                    ->native(false)
                                    ->displayFormat('d M Y')
                                    ->visible(fn (Get $get) => self::status($get('status')) === TransactionStatus::Paid),
                            ]),

                        Section::make('Soporte')
                            ->schema([
                                TextInput::make('reference')
                                    ->label('Referencia')
                                    ->placeholder('N.º factura, cuenta de cobro…')
                                    ->maxLength(255),

                                // Disco privado: las facturas no deben quedar en una URL pública.
                                FileUpload::make('attachment')
                                    ->label('Factura / comprobante')
                                    ->disk('local')
                                    ->directory('finance/attachments')
                                    ->visibility('private')
                                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                                    ->maxSize(5120)
                                    ->openable()
                                    ->downloadable(),

                                Textarea::make('notes')
                                    ->label('Notas')
                                    ->rows(3),
                            ]),
                    ]),
            ]);
    }

    public static function type(mixed $value): string
    {
        return $value instanceof TransactionType ? $value->value : (string) ($value ?: TransactionType::Income->value);
    }

    public static function status(mixed $value): ?TransactionStatus
    {
        return $value instanceof TransactionStatus ? $value : TransactionStatus::tryFrom((string) $value);
    }
}

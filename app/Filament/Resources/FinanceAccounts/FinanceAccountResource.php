<?php

namespace App\Filament\Resources\FinanceAccounts;

use App\Enums\AccountType;
use App\Enums\Currency;
use App\Filament\Resources\FinanceAccounts\Pages\ManageFinanceAccounts;
use App\Models\FinanceAccount;
use App\Support\Money;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class FinanceAccountResource extends Resource
{
    protected static ?string $model = FinanceAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'cuenta';

    protected static ?string $slug = 'accounts';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Nombre')->placeholder('Bancolombia ahorros, Nequi…')->required(),
            Select::make('type')->label('Tipo')->options(AccountType::class)->default(AccountType::Bank)->required(),
            Select::make('currency')->label('Moneda')->options(Currency::class)->default(Currency::base())->required(),
            TextInput::make('initial_balance')->label('Saldo inicial')->numeric()->prefix('$')->default(0)
                ->helperText('Lo que tenía la cuenta antes de registrar movimientos aquí.'),
            Toggle::make('is_active')->label('Activa')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Cuenta')->searchable()
                    ->description(fn (FinanceAccount $record) => $record->type?->getLabel()),
                TextColumn::make('currency')->label('Moneda')->formatStateUsing(fn ($state) => $state->value),
                TextColumn::make('balance')->label('Saldo actual')->alignEnd()
                    ->state(fn (FinanceAccount $record) => $record->balance())
                    ->formatStateUsing(fn ($state, FinanceAccount $record) => Money::format($state, $record->currency))
                    ->color(fn ($state) => $state < 0 ? 'danger' : null)
                    ->weight('bold'),
                ToggleColumn::make('is_active')->label('Activa'),
            ])
            ->recordActions([EditAction::make()->iconButton(), DeleteAction::make()->iconButton()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageFinanceAccounts::route('/')];
    }
}

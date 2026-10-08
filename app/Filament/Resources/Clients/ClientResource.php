<?php

namespace App\Filament\Resources\Clients;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filament\Resources\Clients\Pages\ManageClients;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Client;
use App\Support\Money;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Nombre de contacto')->required()->maxLength(255),
            TextInput::make('company')->label('Empresa')->maxLength(255),
            TextInput::make('document')->label('NIT / Cédula')->maxLength(50),
            TextInput::make('city')->label('Ciudad')->maxLength(100),
            TextInput::make('email')->label('Correo')->email(),
            TextInput::make('phone')->label('Teléfono')->tel(),
            Textarea::make('notes')->label('Notas')->rows(3)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withSum(['transactions as billed' => fn (Builder $q) => $q
                    ->where('type', TransactionType::Income)->where('status', TransactionStatus::Paid)], 'amount_base')
                ->withSum(['transactions as owed' => fn (Builder $q) => $q
                    ->where('type', TransactionType::Income)->where('status', TransactionStatus::Pending)], 'amount_base'))
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->label('Cliente')->searchable()->sortable()
                    ->description(fn (Client $record) => $record->company),
                TextColumn::make('email')->label('Correo')->searchable()->copyable()->toggleable(),
                TextColumn::make('phone')->label('Teléfono')->toggleable(),
                TextColumn::make('billed')->label('Facturado')->sortable()->alignEnd()
                    ->formatStateUsing(fn ($state) => Money::format($state))->placeholder(Money::format(0)),
                TextColumn::make('owed')->label('Te debe')->sortable()->alignEnd()
                    ->formatStateUsing(fn ($state) => Money::format($state))
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'gray')
                    ->placeholder('—'),
            ])
            ->recordActions([
                Action::make('transactions')
                    ->label('Movimientos')
                    ->icon(Heroicon::OutlinedBanknotes)
                    ->iconButton()
                    ->url(fn (Client $record) => TransactionResource::getUrl('index', [
                        'filters' => ['client' => ['value' => $record->getKey()]],
                    ])),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageClients::route('/')];
    }
}

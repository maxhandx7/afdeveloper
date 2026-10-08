<?php

namespace App\Filament\Resources\RecurringTransactions;

use App\Enums\Currency;
use App\Enums\Frequency;
use App\Enums\TransactionType;
use App\Filament\Resources\RecurringTransactions\Pages\ManageRecurringTransactions;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Models\RecurringTransaction;
use App\Support\Money;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class RecurringTransactionResource extends Resource
{
    protected static ?string $model = RecurringTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPath;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'recurrente';

    protected static ?string $pluralModelLabel = 'recurrentes';

    protected static ?string $slug = 'recurring';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            ToggleButtons::make('type')->label('Tipo')->options(TransactionType::class)->inline()->required()
                ->default(TransactionType::Expense)->live()
                ->afterStateUpdated(fn (Set $set) => $set('category_id', null))
                ->columnSpanFull(),
            TextInput::make('description')->label('Descripción')->placeholder('VPS Contabo, dominio afdeveloper.com…')
                ->required()->columnSpanFull(),
            TextInput::make('amount')->label('Monto')->numeric()->minValue(0.01)->prefix('$')->required(),
            Select::make('currency')->label('Moneda')->options(Currency::class)->default(Currency::base())->required(),
            Select::make('frequency')->label('Se repite')->options(Frequency::class)->default(Frequency::Monthly)->required(),
            DatePicker::make('next_due_date')->label('Próximo cobro')->native(false)->default(today())->required(),
            Select::make('category_id')->label('Categoría')
                ->relationship('category', 'name', fn (Builder $q, Get $get) => $q->where('type', TransactionForm::type($get('type'))))
                ->preload(),
            Select::make('finance_account_id')->label('Cuenta')->relationship('account', 'name')->preload(),
            Select::make('client_id')->label('Cliente')->relationship('client', 'name')->searchable()->preload()
                ->helperText('Para igualas o mensualidades que te paga un cliente.'),
            DatePicker::make('ends_at')->label('Termina el')->native(false)->helperText('Vacío = indefinido.'),
            Toggle::make('is_active')->label('Activo')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('next_due_date')
            ->description('Cada mañana se crean como "pendientes" los que ya vencieron. Tú solo los marcas como pagados.')
            ->columns([
                TextColumn::make('description')->label('Descripción')->searchable()
                    ->description(fn (RecurringTransaction $record) => $record->frequency->getLabel()),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('amount')->label('Monto')->alignEnd()
                    ->formatStateUsing(fn ($state, RecurringTransaction $record) => Money::format($state, $record->currency)),
                TextColumn::make('next_due_date')->label('Próximo')->date('d M Y')->sortable()
                    ->color(fn ($state) => $state?->isPast() ? 'danger' : null),
                ToggleColumn::make('is_active')->label('Activo'),
            ])
            ->recordActions([
                Action::make('generate')
                    ->label('Generar ahora')
                    ->icon(Heroicon::OutlinedBolt)
                    ->iconButton()
                    ->tooltip('Crea el movimiento de este periodo sin esperar al cron')
                    ->requiresConfirmation()
                    ->visible(fn (RecurringTransaction $record) => $record->is_active)
                    ->action(function (RecurringTransaction $record) {
                        $count = $record->generateDueTransactions($record->next_due_date);
                        Notification::make()->success()->title("{$count} movimiento(s) creado(s)")->send();
                    }),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageRecurringTransactions::route('/')];
    }
}

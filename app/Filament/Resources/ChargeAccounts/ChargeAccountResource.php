<?php

namespace App\Filament\Resources\ChargeAccounts;

use App\Enums\BillingDocumentStatus;
use App\Enums\BillingDocumentType;
use App\Filament\Resources\ChargeAccounts\Pages\CreateChargeAccount;
use App\Filament\Resources\ChargeAccounts\Pages\EditChargeAccount;
use App\Filament\Resources\ChargeAccounts\Pages\ListChargeAccounts;
use App\Filament\Support\Billing\BillingDocumentForm;
use App\Filament\Support\Billing\BillingDocumentTable;
use App\Models\BillingDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ChargeAccountResource extends Resource
{
    public const TYPE = BillingDocumentType::ChargeAccount;

    protected static ?string $model = BillingDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 7;

    protected static ?string $modelLabel = 'cuenta de cobro';

    protected static ?string $pluralModelLabel = 'cuentas de cobro';

    protected static ?string $slug = 'charge-accounts';

    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Schema $schema): Schema
    {
        return BillingDocumentForm::configure($schema, self::TYPE);
    }

    public static function table(Table $table): Table
    {
        return BillingDocumentTable::configure($table, self::TYPE);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', self::TYPE)->with('client');
    }

    public static function canEdit(Model $record): bool
    {
        return $record->status->isEditable();
    }

    public static function canDelete(Model $record): bool
    {
        return $record->status === BillingDocumentStatus::Draft;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChargeAccounts::route('/'),
            'create' => CreateChargeAccount::route('/create'),
            'edit' => EditChargeAccount::route('/{record}/edit'),
        ];
    }
}

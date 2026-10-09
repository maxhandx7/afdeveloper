<?php

namespace App\Filament\Resources\Quotes;

use App\Enums\BillingDocumentStatus;
use App\Enums\BillingDocumentType;
use App\Filament\Resources\Quotes\Pages\CreateQuote;
use App\Filament\Resources\Quotes\Pages\EditQuote;
use App\Filament\Resources\Quotes\Pages\ListQuotes;
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

class QuoteResource extends Resource
{
    public const TYPE = BillingDocumentType::Quote;

    protected static ?string $model = BillingDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'cotización';

    protected static ?string $pluralModelLabel = 'cotizaciones';

    protected static ?string $slug = 'quotes';

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
            'index' => ListQuotes::route('/'),
            'create' => CreateQuote::route('/create'),
            'edit' => EditQuote::route('/{record}/edit'),
        ];
    }
}

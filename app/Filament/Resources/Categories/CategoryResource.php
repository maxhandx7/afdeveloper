<?php

namespace App\Filament\Resources\Categories;

use App\Enums\TransactionType;
use App\Filament\Resources\Categories\Pages\ManageCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Finanzas';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'categoría';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            ToggleButtons::make('type')->label('Tipo')->options(TransactionType::class)->inline()->required()
                ->default(TransactionType::Expense),
            TextInput::make('name')->label('Nombre')->required()->maxLength(80)
                ->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, $get) => $rule->where(
                    'type', $get('type') instanceof TransactionType ? $get('type')->value : $get('type'),
                )),
            ColorPicker::make('color')->label('Color'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                ColorColumn::make('color')->label(''),
                TextColumn::make('name')->label('Nombre')->searchable(),
                TextColumn::make('type')->label('Tipo')->badge(),
                TextColumn::make('transactions_count')->label('Movimientos')->counts('transactions')->alignEnd(),
            ])
            ->filters([SelectFilter::make('type')->label('Tipo')->options(TransactionType::class)])
            ->recordActions([EditAction::make()->iconButton(), DeleteAction::make()->iconButton()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCategories::route('/')];
    }
}

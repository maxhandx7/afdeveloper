<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\ManageTestimonials;
use App\Filament\Support\ContentFields;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'testimonio';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Nombre')->required(),
            TextInput::make('role')->label('Cargo / empresa')->placeholder('Gerente, Big Group'),
            Textarea::make('description')->label('Testimonio')->rows(4)->required()->columnSpanFull(),
            ContentFields::image('image', 'images/testimonials')->label('Foto o logo')->avatar(),
            Toggle::make('is_published')->label('Publicado')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->disk('web')->circular()->imageSize(40),
                TextColumn::make('name')->label('Nombre')->searchable()->description(fn (Testimonial $r) => $r->role),
                TextColumn::make('description')->label('Testimonio')->limit(80)->wrap(),
                ToggleColumn::make('is_published')->label('Publicado'),
            ])
            ->recordActions([EditAction::make()->iconButton(), DeleteAction::make()->iconButton()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageTestimonials::route('/')];
    }
}

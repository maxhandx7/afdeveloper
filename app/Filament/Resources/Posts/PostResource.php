<?php

namespace App\Filament\Resources\Posts;

use App\Enums\PublishStatus;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Support\ContentFields;
use App\Models\Post;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'artículo';

    protected static ?string $navigationLabel = 'Blog';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Grid::make(1)->columnSpan(['lg' => 2])->schema([
                Section::make()->schema([
                    TextInput::make('title')->label('Título')->required()->maxLength(255),
                    Textarea::make('excerpt')->label('Resumen')->rows(2)->maxLength(300)
                        ->helperText('Aparece en la tarjeta del blog. Si lo dejas vacío se toma del contenido.'),
                    ContentFields::richText('long_description', 'Contenido')->required(),
                ]),
            ]),
            Grid::make(1)->columnSpan(['lg' => 1])->schema([
                Section::make('Publicación')->schema([
                    ToggleButtons::make('status')->label('Estado')->options(PublishStatus::class)
                        ->default(PublishStatus::Hidden)->inline()->required(),
                    DateTimePicker::make('published_at')->label('Fecha de publicación')->native(false)
                        ->default(now())->helperText('Si es futura, el artículo sale solo ese día.'),
                    ContentFields::image(),
                ]),
                Section::make('SEO')->collapsed()->schema([
                    TextInput::make('slug')->label('Slug')->unique(ignoreRecord: true)->alphaDash()
                        ->helperText('Se genera del título si lo dejas vacío.'),
                    Textarea::make('meta_description')->label('Meta descripción')->rows(3)->maxLength(160),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image')->label('')->disk('web')->square()->imageSize(48),
                TextColumn::make('title')->label('Título')->searchable()->wrap()
                    ->description(fn (Post $record) => $record->readingMinutes().' min de lectura'),
                TextColumn::make('status')->label('Estado')->badge(),
                TextColumn::make('published_at')->label('Publicado')->date('d M Y')->sortable(),
            ])
            ->filters([SelectFilter::make('status')->label('Estado')->options(PublishStatus::class)])
            ->recordActions([
                Action::make('view')->label('Ver')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->iconButton()->color('gray')
                    ->url(fn (Post $record) => route('blog.show', $record))->openUrlInNewTab()
                    ->visible(fn (Post $record) => $record->status === PublishStatus::Published),
                ContentFields::togglePublishAction(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}

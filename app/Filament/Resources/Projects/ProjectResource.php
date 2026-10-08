<?php

namespace App\Filament\Resources\Projects;

use App\Enums\PublishStatus;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Support\ContentFields;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'proyecto';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(3)->components([
            Grid::make(1)->columnSpan(['lg' => 2])->schema([
                Section::make()->columns(2)->schema([
                    TextInput::make('title')->label('Título')->required()->maxLength(255)->columnSpanFull(),
                    TextInput::make('link')->label('URL del proyecto')->url()->required(),
                    TextInput::make('repo_url')->label('Repositorio')->url(),
                    Textarea::make('description')->label('Resumen')->rows(2)->maxLength(500)->columnSpanFull(),
                    ContentFields::richText('long_description', 'Descripción completa'),
                ]),
            ]),
            Grid::make(1)->columnSpan(['lg' => 1])->schema([
                Section::make('Publicación')->schema([
                    ToggleButtons::make('status')->label('Estado')->options(PublishStatus::class)
                        ->default(PublishStatus::Published)->inline()->required(),
                    Toggle::make('is_featured')->label('Destacar en el inicio')
                        ->helperText('Se muestran máximo 3.'),
                    TextInput::make('sort_order')->label('Orden')->numeric()->default(0),
                    TextInput::make('slug')->label('Slug')->helperText('Se genera solo si lo dejas vacío.')
                        ->unique(ignoreRecord: true)->alphaDash(),
                ]),
                Section::make('Imagen y stack')->schema([
                    ContentFields::image(),
                    TagsInput::make('tech_stack')->label('Tecnologías')
                        ->suggestions(['Laravel', 'PHP', 'React', 'Vue', 'Filament', 'Livewire', 'NodeJS',
                            'MySQL', 'WordPress', 'WooCommerce', 'Dolibarr', 'Docker', 'Azure']),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->disk('web')->square()->imageSize(48),
                TextColumn::make('title')->label('Título')->searchable()->sortable()
                    ->description(fn (Project $record) => implode(' · ', $record->tech_stack ?? [])),
                TextColumn::make('status')->label('Estado')->badge(),
                ToggleColumn::make('is_featured')->label('Destacado'),
                TextColumn::make('updated_at')->label('Actualizado')->since()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([SelectFilter::make('status')->label('Estado')->options(PublishStatus::class)])
            ->recordActions([
                Action::make('view')->label('Ver en el sitio')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->iconButton()->color('gray')
                    ->url(fn (Project $record) => route('projects.show', $record))->openUrlInNewTab()
                    ->visible(fn (Project $record) => $record->status === PublishStatus::Published),
                ContentFields::togglePublishAction(),
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}

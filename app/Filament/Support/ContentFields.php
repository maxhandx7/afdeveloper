<?php

namespace App\Filament\Support;

use App\Enums\PublishStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

/** Piezas repetidas entre los recursos de contenido público. */
class ContentFields
{
    /** Imagen pública guardada en public/images (compatible con las rutas viejas). */
    public static function image(string $name = 'image', string $directory = 'images'): FileUpload
    {
        return FileUpload::make($name)
            ->label('Imagen')
            ->disk('web')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->maxSize(4096);
    }

    public static function richText(string $name, string $label): RichEditor
    {
        return RichEditor::make($name)
            ->label($label)
            ->fileAttachmentsDisk('web')
            ->fileAttachmentsDirectory('images/content')
            ->fileAttachmentsVisibility('public')
            ->columnSpanFull();
    }

    public static function togglePublishAction(): Action
    {
        return Action::make('togglePublish')
            ->label(fn (Model $record) => $record->status === PublishStatus::Published ? 'Ocultar' : 'Publicar')
            ->icon(fn (Model $record) => $record->status === PublishStatus::Published ? Heroicon::OutlinedEyeSlash : Heroicon::OutlinedEye)
            ->iconButton()
            ->color('gray')
            ->action(function (Model $record) {
                $record->update([
                    'status' => $record->status === PublishStatus::Published ? PublishStatus::Hidden : PublishStatus::Published,
                ]);

                Notification::make()->success()->title($record->status->getLabel())->send();
            });
    }
}

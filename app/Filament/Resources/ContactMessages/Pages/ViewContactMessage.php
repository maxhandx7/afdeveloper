<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var ContactMessage $message */
        $message = $this->getRecord();
        $message->markAsRead();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')->label('Responder por correo')->icon(Heroicon::OutlinedArrowUturnLeft)
                ->url(fn () => 'mailto:'.$this->getRecord()->email.'?subject='.rawurlencode('Re: tu mensaje en afdeveloper.com')),
            Action::make('markUnread')->label('Marcar como no leído')->color('gray')
                ->action(function () {
                    $this->getRecord()->forceFill(['read_at' => null])->save();
                    $this->redirect(ContactMessageResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}

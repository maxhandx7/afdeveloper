<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use App\Services\Waha\WahaChannel;
use App\Services\Waha\WahaClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

/** Te avisa por WhatsApp cuando alguien escribe desde el formulario del sitio. */
class NewContactMessageWhatsApp extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public ContactMessage $contactMessage) {}

    public function via(object $notifiable): array
    {
        return app(WahaClient::class)->enabled() ? [WahaChannel::class] : [];
    }

    public function toWaha(object $notifiable): string
    {
        $m = $this->contactMessage;

        return "📩 Nuevo mensaje en afdeveloper.com\n\n"
            ."*{$m->name}* ({$m->email}".($m->phone ? ", {$m->phone}" : '').")\n\n"
            .Str::limit($m->message, 600)."\n\n"
            .url('/admin/messages/'.$m->getKey());
    }
}

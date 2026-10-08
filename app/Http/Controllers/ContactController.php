<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use App\Notifications\NewContactMessageWhatsApp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function store(ContactRequest $request): JsonResponse
    {
        // Honeypot: un humano nunca ve ni llena el campo "website".
        // Al bot le respondemos "éxito" para que no aprenda nada.
        if ($request->filled('website')) {
            return response()->json(['success' => true]);
        }

        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'phone', 'message']),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        // En cola: el visitante no espera a que el SMTP responda.
        Mail::to(config('afdeveloper.contact_email'))->queue(new ContactMessageReceived($message));

        if ($phone = config('services.waha.admin_phone')) {
            Notification::route('waha', $phone)->notify(new NewContactMessageWhatsApp($message));
        }

        return response()->json(['success' => true]);
    }
}

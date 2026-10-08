<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><title>Nuevo mensaje</title></head>
<body style="margin:0;padding:24px;background:#f5f4f0;font-family:Arial,Helvetica,sans-serif;color:#0e0e0f">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border:1px solid #dedad3">
        <tr><td style="padding:28px 32px;border-bottom:1px solid #dedad3">
            <p style="margin:0;font-size:12px;letter-spacing:2px;color:#8a96aa">AFDEVELOPER.COM · CONTACTO</p>
            <h1 style="margin:10px 0 0;font-size:22px;font-weight:normal">Nuevo mensaje de {{ $contactMessage->name }}</h1>
        </td></tr>
        <tr><td style="padding:28px 32px;font-size:15px;line-height:1.7;color:#1c1c1e">
            {!! nl2br(e($contactMessage->message)) !!}
        </td></tr>
        <tr><td style="padding:20px 32px;background:#f5f4f0;font-size:14px;line-height:1.8">
            <strong>Correo:</strong> <a href="mailto:{{ $contactMessage->email }}" style="color:#5a6478">{{ $contactMessage->email }}</a><br>
            @if ($contactMessage->phone)
                <strong>Teléfono:</strong> {{ $contactMessage->phone }}
                · <a href="https://wa.me/{{ preg_replace('/\D/', '', $contactMessage->phone) }}" style="color:#5a6478">WhatsApp</a><br>
            @endif
            <strong>Recibido:</strong> {{ $contactMessage->created_at?->translatedFormat('d M Y, h:i A') }}
        </td></tr>
        <tr><td style="padding:18px 32px;font-size:12px;color:#8a96aa">
            Responde directamente a este correo: le llega a {{ $contactMessage->name }}.
            También lo tienes en <a href="{{ url('/admin/messages') }}" style="color:#5a6478">la bandeja del panel</a>.
        </td></tr>
    </table>
</body>
</html>

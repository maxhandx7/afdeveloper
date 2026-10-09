<!DOCTYPE html>
<html lang="es">
<body style="margin:0;padding:24px;background:#f5f7fa;font-family:Arial,Helvetica,sans-serif;color:#0f1e33">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#fff;border:1px solid #dce3ec">
        <tr><td style="padding:28px 32px;border-bottom:3px solid #2546ff">
            <p style="margin:0;font-size:13px;color:#5a6a80">{{ $document->type->getLabel() }}</p>
            <h1 style="margin:6px 0 0;font-size:22px">{{ $document->number }}</h1>
        </td></tr>
        <tr><td style="padding:28px 32px;font-size:15px;line-height:1.7">
            <p style="margin:0 0 14px">Hola {{ strtok(trim($document->client->name), ' ') }},</p>
            <p style="margin:0 0 14px">
                Adjunto encontrarás la {{ mb_strtolower($document->type->getLabel()) }} <strong>{{ $document->number }}</strong>
                por <strong>${{ number_format((float) $document->total, 0, ',', '.') }}</strong>@if ($document->due_date && ! $document->isQuote()), con vencimiento el {{ $document->due_date->translatedFormat('j \d\e F \d\e Y') }}@endif.
            </p>
            <p style="margin:0 0 22px">También puedes verla en línea:</p>
            <a href="{{ $document->publicUrl() }}" style="display:inline-block;background:#2546ff;color:#fff;text-decoration:none;padding:12px 22px;border-radius:6px;font-weight:bold">Ver documento</a>
        </td></tr>
        <tr><td style="padding:20px 32px;background:#f5f7fa;font-size:13px;color:#5a6a80">
            {{ $business->setting('billing.holder_name', $business->name) }} · {{ $business->mail }}
        </td></tr>
    </table>
</body>
</html>

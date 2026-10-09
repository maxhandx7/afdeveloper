@php
    $isQuote = $doc->isQuote();
    $money = fn ($v) => '$'.number_format((float) $v, 0, ',', '.');
    $holder = $billing['holder_name'] ?? $business->name;
    $holderDoc = $billing['holder_document'] ?? null;
    $city = $billing['city'] ?? 'Cali';
    $logo = public_path('image/AFDEVELOPER_LOGO.png');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>{{ $doc->type->getLabel() }} {{ $doc->number }}</title>
<style>
    @page { margin: 42px 52px 60px; }
    * { font-family: 'DejaVu Sans', sans-serif; }
    body { color: #0f1e33; font-size: 10.5px; line-height: 1.5; }
    .muted { color: #5a6a80; }
    .signal { color: #2546ff; }
    table { width: 100%; border-collapse: collapse; }
    .head td { vertical-align: top; }
    .doc-title { font-size: 19px; font-weight: bold; letter-spacing: .5px; margin: 0; }
    .doc-number { font-size: 12px; font-weight: bold; color: #2546ff; margin: 2px 0 0; }
    .rule { border-top: 2px solid #0f1e33; margin: 14px 0 18px; }
    .label { font-size: 8.5px; text-transform: uppercase; letter-spacing: .8px; color: #5a6a80; margin: 0 0 3px; }
    .party { font-size: 12px; font-weight: bold; margin: 0; }
    .box { border: 1px solid #dce3ec; padding: 12px 14px; }
    .amount { background: #f5f7fa; border-left: 3px solid #2546ff; padding: 12px 14px; margin: 18px 0; }
    .amount .words { font-size: 11.5px; font-weight: bold; margin: 0; }
    .amount .figure { font-size: 16px; font-weight: bold; margin: 2px 0 0; }
    .items th { background: #0f1e33; color: #fff; font-size: 8.5px; text-transform: uppercase; letter-spacing: .6px; padding: 7px 8px; text-align: left; }
    .items td { padding: 8px; border-bottom: 1px solid #dce3ec; vertical-align: top; }
    .items .num { text-align: right; white-space: nowrap; }
    .items tfoot td { border: 0; font-weight: bold; font-size: 12px; padding-top: 10px; }
    .note { font-size: 9.5px; color: #2b3a50; margin: 16px 0 0; }
    .sign { margin-top: 46px; width: 260px; }
    .sign img { height: 56px; margin-bottom: -6px; }
    .sign-line { border-top: 1px solid #0f1e33; padding-top: 5px; }
    .footer { position: fixed; bottom: -36px; left: 0; right: 0; font-size: 8px; color: #5a6a80; text-align: center; }
</style>
</head>
<body>

<div class="footer">{{ $business->name }} · {{ $business->mail }}@if ($business->phone) · {{ $business->phone }}@endif · afdeveloper.com</div>

<table class="head">
    <tr>
        <td style="width:55%">
            @if (is_file($logo))<img src="{{ $logo }}" style="height:34px"><br>@endif
        </td>
        <td style="text-align:right">
            <p class="doc-title">{{ mb_strtoupper($doc->type->getLabel()) }}</p>
            <p class="doc-number">No. {{ $doc->number }}</p>
            <p class="muted" style="margin:4px 0 0">{{ $city }}, {{ $doc->issue_date->translatedFormat('j \d\e F \d\e Y') }}</p>
        </td>
    </tr>
</table>

<div class="rule"></div>

@if ($isQuote)
    {{-- ── COTIZACIÓN ─────────────────────────────── --}}
    <table>
        <tr>
            <td style="width:50%; padding-right:10px; vertical-align:top">
                <div class="box">
                    <p class="label">Para</p>
                    <p class="party">{{ $doc->client->company ?: $doc->client->name }}</p>
                    @if ($doc->client->company)<p style="margin:0">Atn. {{ $doc->client->name }}</p>@endif
                    @if ($doc->client->document)<p class="muted" style="margin:0">NIT / C.C. {{ $doc->client->document }}</p>@endif
                </div>
            </td>
            <td style="width:50%; padding-left:10px; vertical-align:top">
                <div class="box">
                    <p class="label">De</p>
                    <p class="party">{{ $holder }}</p>
                    @if ($holderDoc)<p class="muted" style="margin:0">C.C. {{ $holderDoc }}</p>@endif
                    @if ($doc->due_date)<p style="margin:0">Válida hasta el {{ $doc->due_date->translatedFormat('j \d\e F \d\e Y') }}</p>@endif
                </div>
            </td>
        </tr>
    </table>
@else
    {{-- ── CUENTA DE COBRO (formato colombiano) ───── --}}
    <p class="party" style="font-size:13px">{{ $doc->client->company ?: $doc->client->name }}</p>
    @if ($doc->client->document)<p style="margin:0">NIT / C.C. {{ $doc->client->document }}</p>@endif
    @if ($doc->client->city)<p class="muted" style="margin:0">{{ $doc->client->city }}</p>@endif

    <p class="label" style="margin-top:18px">Debe a</p>
    <p class="party" style="font-size:13px">{{ mb_strtoupper($holder) }}</p>
    @if ($holderDoc)<p style="margin:0">C.C. {{ $holderDoc }}</p>@endif

    <div class="amount">
        <p class="label">La suma de</p>
        <p class="words">{{ $doc->totalInWords() }}</p>
        <p class="figure">{{ $money($doc->total) }}</p>
    </div>

    <p class="label">Por concepto de</p>
@endif

<table class="items" style="margin-top:{{ $isQuote ? '20px' : '4px' }}">
    <thead>
        <tr>
            <th>Descripción</th>
            <th class="num" style="width:60px">Cant.</th>
            <th class="num" style="width:95px">Valor unit.</th>
            <th class="num" style="width:100px">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($doc->items as $item)
            <tr>
                <td>{!! nl2br(e($item['description'])) !!}</td>
                <td class="num">{{ rtrim(rtrim(number_format($item['quantity'], 2, ',', '.'), '0'), ',') }}</td>
                <td class="num">{{ $money($item['unit_price']) }}</td>
                <td class="num">{{ $money($item['quantity'] * $item['unit_price']) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="num">TOTAL {{ $doc->currency->value }}</td>
            <td class="num signal">{{ $money($doc->total) }}</td>
        </tr>
    </tfoot>
</table>

@if ($isQuote)
    <p class="muted" style="margin:6px 0 0; text-align:right; font-size:9px">{{ $doc->totalInWords() }}</p>
@endif

@if ($doc->notes)
    <p class="label" style="margin-top:18px">Observaciones</p>
    <p style="margin:0">{!! nl2br(e($doc->notes)) !!}</p>
@endif

@if (! $isQuote)
    @if (! empty($billing['bank_details']))
        <p class="label" style="margin-top:18px">Datos para el pago</p>
        <p style="margin:0">{!! nl2br(e($billing['bank_details'])) !!}</p>
    @endif

    @if ($billing['iva_note_enabled'] ?? true)
        <p class="note">{{ ($billing['iva_note'] ?? null) ?: \App\Services\Billing\BillingDocuments::DEFAULT_IVA_NOTE }}</p>
    @endif

    <div class="sign">
        @if ($signature)<img src="{{ $signature }}"><br>@endif
        <div class="sign-line">
            <strong>{{ mb_strtoupper($holder) }}</strong><br>
            @if ($holderDoc)C.C. {{ $holderDoc }}@endif
        </div>
    </div>
@endif

</body>
</html>

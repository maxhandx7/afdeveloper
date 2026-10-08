<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\CrediTrack\CrediTrackSync;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CrediTrackWebhookController extends Controller
{
    public function __invoke(Request $request, CrediTrackSync $sync): JsonResponse
    {
        if (! $this->hasValidSignature($request)) {
            return response()->json(['message' => 'Firma inválida.'], 401);
        }

        $payload = $request->json()->all();
        $result = $sync->handle($payload['event'] ?? '', $payload['data'] ?? []);

        return response()->json(['ok' => true, 'result' => $result]);
    }

    /** X-CrediTrack-Signature: t=<timestamp>,v1=<hmac_sha256(secret, "t.body")> */
    private function hasValidSignature(Request $request): bool
    {
        $secret = config('services.creditrack.webhook_secret');
        if (blank($secret)) {
            return false;
        }

        parse_str(str_replace(',', '&', (string) $request->header('X-CrediTrack-Signature')), $parts);
        $timestamp = (int) ($parts['t'] ?? 0);
        $signature = (string) ($parts['v1'] ?? '');

        // Rechaza eventos viejos: alguien que capture una petición no puede repetirla después.
        if (abs(time() - $timestamp) > config('services.creditrack.tolerance_seconds', 300)) {
            return false;
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$request->getContent(), $secret);

        return hash_equals($expected, $signature);
    }
}

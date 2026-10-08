<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrediTrackWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function send(array $payload, ?string $secret = 'secreto', ?int $ts = null)
    {
        $body = json_encode($payload);
        $ts ??= time();
        $sig = hash_hmac('sha256', $ts.'.'.$body, (string) $secret);

        return $this->call('POST', '/webhooks/creditrack', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_CREDITRACK_SIGNATURE' => "t={$ts},v1={$sig}",
        ], $body);
    }

    private function payment(): array
    {
        return ['id' => 'e1', 'event' => 'payment.created', 'data' => [
            'id' => 5, 'amount' => 600000, 'date' => '2026-10-09', 'remaining_balance' => 600000, 'notes' => null,
            'loan' => ['id' => 7, 'amount' => 1000000, 'total_amount' => 1200000, 'start_date' => '2026-10-01', 'due_date' => '2026-12-01',
                'client' => ['name' => 'Pedro', 'document' => '111', 'phone' => '3001234567', 'email' => null]],
        ]];
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.creditrack.webhook_secret' => 'secreto']);
    }

    public function test_registra_el_pago_como_ingreso_una_sola_vez(): void
    {
        $this->send($this->payment())->assertOk()->assertJsonPath('result', 'created');
        $this->send($this->payment())->assertOk()->assertJsonPath('result', 'updated');

        $this->assertSame(1, Transaction::count());
        $this->assertEquals(600000, Transaction::first()->amount_base);
    }

    public function test_rechaza_firmas_invalidas_o_viejas(): void
    {
        $this->send($this->payment(), 'otro-secreto')->assertUnauthorized();
        $this->send($this->payment(), 'secreto', time() - 3600)->assertUnauthorized();
        $this->assertSame(0, Transaction::count());
    }

    public function test_sin_secreto_configurado_no_acepta_nada(): void
    {
        config(['services.creditrack.webhook_secret' => null]);
        $this->send($this->payment())->assertUnauthorized();
    }
}

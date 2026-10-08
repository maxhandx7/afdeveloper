<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Models\Client;
use App\Models\FinanceAccount;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_convierte_moneda_extranjera_a_la_base(): void
    {
        $t = Transaction::create([
            'type' => 'income', 'description' => 'Cliente USA', 'amount' => 500,
            'currency' => 'USD', 'exchange_rate' => 4150, 'date' => '2026-10-01',
        ]);

        $this->assertEquals(2075000, $t->amount_base);
    }

    public function test_en_moneda_base_la_tasa_siempre_es_1(): void
    {
        $t = Transaction::create(['type' => 'income', 'description' => 'x', 'amount' => 1000, 'exchange_rate' => 999, 'date' => '2026-10-01']);

        $this->assertEquals(1000, $t->amount_base);
    }

    public function test_marcar_como_pagado_con_la_tasa_real(): void
    {
        $t = Transaction::create([
            'type' => 'income', 'description' => 'x', 'amount' => 500, 'currency' => 'USD',
            'exchange_rate' => 4150, 'date' => '2026-10-01', 'status' => 'pending',
        ]);
        $this->assertNull($t->paid_at);

        $t->exchange_rate = 4000;
        $t->markAsPaid(Carbon::parse('2026-10-05'));

        $this->assertSame(TransactionStatus::Paid, $t->fresh()->status);
        $this->assertEquals(2000000, $t->fresh()->amount_base);
        $this->assertSame('2026-10-05', $t->fresh()->paid_at->toDateString());
    }

    public function test_saldo_de_cuenta_ignora_pendientes(): void
    {
        $account = FinanceAccount::create(['name' => 'Nequi', 'initial_balance' => 100000]);
        $base = ['finance_account_id' => $account->id, 'date' => '2026-10-01'];

        Transaction::create([...$base, 'type' => 'income', 'description' => 'a', 'amount' => 2000000]);
        Transaction::create([...$base, 'type' => 'expense', 'description' => 'b', 'amount' => 45000]);
        Transaction::create([...$base, 'type' => 'expense', 'description' => 'c', 'amount' => 999999, 'status' => 'pending']);

        $this->assertEquals(2055000, $account->balance());
    }

    public function test_cliente_sabe_cuanto_debe(): void
    {
        $client = Client::create(['name' => 'Big Group']);
        Transaction::create(['type' => 'income', 'description' => 'x', 'amount' => 1000000, 'date' => '2026-10-01', 'client_id' => $client->id]);
        Transaction::create(['type' => 'income', 'description' => 'y', 'amount' => 500000, 'date' => '2026-10-01', 'client_id' => $client->id, 'status' => 'pending']);

        $this->assertEquals(1000000, $client->totalBilled());
        $this->assertEquals(500000, $client->totalOwed());
    }

    public function test_recurrentes_se_ponen_al_dia_sin_duplicar_ni_desbordar_meses(): void
    {
        $this->travelTo('2026-10-08');

        $vps = RecurringTransaction::create([
            'type' => 'expense', 'description' => 'VPS Contabo', 'amount' => 45000, 'next_due_date' => '2026-07-31',
        ]);

        $this->artisan('finance:recurring')->assertSuccessful();
        $this->artisan('finance:recurring')->assertSuccessful();

        $this->assertSame(
            ['2026-07-31', '2026-08-31', '2026-09-30'],
            $vps->transactions()->orderBy('date')->get()->map(fn ($t) => $t->date->toDateString())->all(),
        );
        $this->assertSame('2026-10-30', $vps->fresh()->next_due_date->toDateString());
        $this->assertTrue($vps->transactions()->get()->every(fn ($t) => $t->status === TransactionStatus::Pending));
    }

    public function test_recurrente_con_fecha_fin_se_desactiva(): void
    {
        $this->travelTo('2026-10-08');

        $r = RecurringTransaction::create([
            'type' => 'expense', 'description' => 'Dominio', 'amount' => 80000,
            'frequency' => 'yearly', 'next_due_date' => '2024-03-01', 'ends_at' => '2025-12-31',
        ]);

        $this->artisan('finance:recurring');

        $this->assertSame(2, $r->transactions()->count());
        $this->assertFalse($r->fresh()->is_active);
    }

    public function test_el_comando_recurrente_esta_programado(): void
    {
        $this->artisan('schedule:list')->expectsOutputToContain('finance:recurring');
    }
}

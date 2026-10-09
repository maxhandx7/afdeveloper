<?php

namespace Tests\Feature;

use App\Enums\BillingDocumentStatus;
use App\Mail\BillingDocumentMail;
use App\Models\BillingDocument;
use App\Models\Business;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Billing\BillingDocuments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BillingDocumentsTest extends TestCase
{
    use RefreshDatabase;

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        Business::create(['name' => 'AF Developer', 'mail' => 'a@a.com', 'address' => 'Cali', 'phone' => '300', 'nit' => '1', 'logo' => 'image/logo.png',
            'configurations' => ['billing' => ['holder_name' => 'Alan Carabali', 'holder_document' => '123']]]);
        $this->client = Client::create(['name' => 'Juan', 'company' => 'Big Group', 'email' => 'juan@x.com', 'phone' => '3001234567']);
    }

    private function doc(string $type = 'cuenta_cobro'): BillingDocument
    {
        return BillingDocument::create(['type' => $type, 'client_id' => $this->client->id, 'items' => [
            ['description' => 'Desarrollo', 'quantity' => 1, 'unit_price' => 1200000],
            ['description' => 'Soporte', 'quantity' => 2, 'unit_price' => 150000],
        ]]);
    }

    public function test_consecutivos_independientes_por_tipo_y_total_en_letras(): void
    {
        $a = $this->doc();
        $b = $this->doc();
        $q = $this->doc('cotizacion');

        $this->assertSame(['CC-0001', 'CC-0002', 'COT-0001'], [$a->number, $b->number, $q->number]);
        $this->assertEquals(1500000, $a->total);
        $this->assertSame('UN MILLÓN QUINIENTOS MIL PESOS M/CTE', $a->totalInWords());
    }

    public function test_marcar_pagada_crea_el_ingreso_una_sola_vez(): void
    {
        $doc = $this->doc();
        $service = app(BillingDocuments::class);

        $service->markAsPaid($doc, Carbon::parse('2026-10-09'));
        $service->markAsPaid($doc->fresh(), Carbon::parse('2026-10-09'));

        $this->assertSame(BillingDocumentStatus::Paid, $doc->fresh()->status);
        $this->assertSame(1, Transaction::count());
        $this->assertEquals(1500000, Transaction::first()->amount_base);
        $this->assertSame('CC-0001', Transaction::first()->reference);
    }

    public function test_cotizacion_se_convierte_en_cuenta_de_cobro(): void
    {
        $quote = $this->doc('cotizacion');
        $cc = app(BillingDocuments::class)->convertToChargeAccount($quote);

        $this->assertSame(BillingDocumentStatus::Accepted, $quote->fresh()->status);
        $this->assertSame('CC-0001', $cc->number);
        $this->assertEquals($quote->total, $cc->total);
        $this->assertTrue($cc->source->is($quote));
    }

    public function test_enviar_por_correo_y_whatsapp(): void
    {
        Mail::fake();
        Http::fake();
        config(['services.waha.enabled' => true, 'services.waha.url' => 'http://waha.test']);
        $doc = $this->doc();

        app(BillingDocuments::class)->send($doc, email: true, whatsapp: true);

        Mail::assertQueued(BillingDocumentMail::class, fn ($m) => $m->hasTo('juan@x.com'));
        Http::assertSent(fn ($r) => str_contains($r['text'], 'CC-0001') && str_contains($r['text'], $doc->publicUrl()));
        $this->assertSame(BillingDocumentStatus::Sent, $doc->fresh()->status);
    }

    public function test_el_enlace_publico_muestra_el_pdf_pero_no_los_borradores(): void
    {
        $doc = $this->doc();
        $this->get($doc->publicUrl())->assertNotFound(); // borrador

        $doc->update(['status' => BillingDocumentStatus::Sent]);
        $this->get($doc->publicUrl())->assertOk()->assertHeader('Content-Type', 'application/pdf');

        $this->get('/documentos/'.str_repeat('a', 40))->assertNotFound();
    }

    public function test_la_vista_previa_exige_login(): void
    {
        $doc = $this->doc();
        $this->get(route('documents.preview', $doc))->assertRedirect();

        $this->actingAs(User::factory()->create())->get(route('documents.preview', $doc))->assertOk();
    }
}

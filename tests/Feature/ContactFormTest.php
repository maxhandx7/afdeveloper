<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private array $valid = [
        'name' => 'María Pérez',
        'email' => 'maria@empresa.co',
        'phone' => '+57 300 000 0000',
        'message' => 'Necesito una integración con la API de Siigo para mi tienda.',
    ];

    public function test_guarda_el_mensaje_y_encola_el_correo(): void
    {
        Mail::fake();

        $this->postJson('/contacto', $this->valid)->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('contact_messages', ['email' => 'maria@empresa.co', 'read_at' => null]);
        Mail::assertQueued(ContactMessageReceived::class, fn ($mail) => $mail->hasTo(config('afdeveloper.contact_email'))
            && $mail->hasReplyTo('maria@empresa.co'));
    }

    public function test_acepta_mensajes_largos_y_sin_telefono(): void
    {
        Mail::fake();

        $this->postJson('/contacto', [...$this->valid, 'phone' => null, 'message' => str_repeat('a', 3000)])->assertOk();

        $this->assertSame(3000, strlen(ContactMessage::first()->message));
    }

    public function test_el_honeypot_engana_a_los_bots(): void
    {
        Mail::fake();

        $this->postJson('/contacto', [...$this->valid, 'website' => 'http://spam.ru'])->assertOk();

        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_valida_los_campos(): void
    {
        $this->postJson('/contacto', ['email' => 'no-es-correo', 'message' => 'corto'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_limita_los_envios_por_minuto(): void
    {
        Mail::fake();

        foreach (range(1, 5) as $i) {
            $this->postJson('/contacto', $this->valid)->assertOk();
        }

        $this->postJson('/contacto', $this->valid)->assertTooManyRequests();
    }
}

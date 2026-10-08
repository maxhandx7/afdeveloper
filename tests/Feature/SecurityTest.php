<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Los huecos que tenía la versión anterior y no deben volver. */
class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_bandeja_ya_no_es_publica(): void
    {
        ContactMessage::create(['name' => 'Juan', 'email' => 'j@x.com', 'message' => 'Mensaje privado']);

        $this->get('/bandeja')->assertNotFound();
        $this->get('/admin/messages')->assertRedirect('/admin/login');
    }

    public function test_no_existe_registro_por_api(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Hacker', 'email' => 'h@x.com', 'password' => 'password123',
        ])->assertNotFound();

        $this->assertDatabaseCount('users', 0);
    }

    public function test_las_rutas_de_prueba_no_existen(): void
    {
        $this->get('/test-summernote')->assertNotFound();
    }

    public function test_el_panel_exige_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_solo_los_correos_autorizados_entran_al_panel(): void
    {
        config(['afdeveloper.admin_emails' => ['alan@afdeveloper.com']]);

        $this->actingAs(User::factory()->create(['email' => 'otro@x.com']))
            ->get('/admin')->assertForbidden();

        $this->actingAs(User::factory()->create(['email' => 'Alan@AFDeveloper.com']))
            ->get('/admin')->assertOk();
    }
}

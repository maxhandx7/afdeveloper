<?php

namespace Tests\Feature;

use App\Enums\PublishStatus;
use App\Models\Business;
use App\Models\Post;
use App\Models\Project;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Business::create([
            'name' => 'AF Developer', 'mail' => 'a@a.com', 'address' => 'Cali', 'phone' => '300',
            'nit' => '1', 'logo' => 'image/alan.jpg', 'description' => '<p>Desarrollador backend</p>',
            'configurations' => ['linkedin' => 'https://linkedin.com/in/alan'],
        ]);
    }

    public function test_las_paginas_publicas_cargan(): void
    {
        Project::create(['title' => 'StreamVault', 'link' => 'https://x.com', 'is_featured' => true]);
        Testimonial::create(['name' => 'Big Group', 'description' => 'Excelente']);

        $this->get('/')->assertOk()->assertSee('StreamVault')->assertSee('Big Group')
            ->assertSee('linkedin.com/in/alan', false);
        $this->get('/proyectos')->assertOk()->assertSee('StreamVault');
        $this->get('/testimonios')->assertOk();
        $this->get('/equipo')->assertOk();
        $this->get('/blog')->assertOk();
    }

    public function test_los_proyectos_ocultos_no_se_muestran(): void
    {
        Project::create(['title' => 'Proyecto secreto', 'link' => 'https://x.com', 'status' => PublishStatus::Hidden]);

        $this->get('/proyectos')->assertOk()->assertDontSee('Proyecto secreto');
        $this->get('/proyectos/proyecto-secreto')->assertNotFound();
    }

    public function test_cada_proyecto_tiene_su_pagina(): void
    {
        Project::create([
            'title' => 'Ventas WooCommerce', 'link' => 'https://ventas.test', 'repo_url' => 'https://github.com/x/ventas',
            'description' => 'Inventario sincronizado con la tienda', 'tech_stack' => ['Laravel', 'WooCommerce'],
            'long_description' => '<p>Detalle del proyecto</p>',
        ]);

        $this->get('/proyectos/ventas-woocommerce')->assertOk()
            ->assertSee('Inventario sincronizado con la tienda')
            ->assertSee('WooCommerce')
            ->assertSee('github.com/x/ventas', false)
            ->assertSee('Detalle del proyecto', false);

        $this->get('/sitemap.xml')->assertSee(route('projects.show', 'ventas-woocommerce'));
    }

    public function test_el_estado_de_disponibilidad_sale_del_panel(): void
    {
        Business::query()->first()->update(['configurations' => ['availability' => 'Disponible para proyectos freelance']]);

        $this->get('/')->assertSee('Disponible para proyectos freelance');
    }

    public function test_articulo_del_blog_por_slug_con_seo(): void
    {
        $post = Post::create([
            'title' => 'Desplegar Laravel en Coolify',
            'long_description' => '<p>Paso a paso.</p>',
            'meta_description' => 'Guía práctica de despliegue',
            'status' => PublishStatus::Published,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/blog/desplegar-laravel-en-coolify')->assertOk()
            ->assertSee('Desplegar Laravel en Coolify')
            ->assertSee('Guía práctica de despliegue')
            ->assertSee('BlogPosting', false);

        $this->get('/sitemap.xml')->assertOk()->assertSee(route('blog.show', $post));
    }

    public function test_borradores_y_programados_dan_404(): void
    {
        Post::create(['title' => 'Borrador', 'long_description' => 'x']);
        Post::create(['title' => 'Futuro', 'long_description' => 'x', 'status' => PublishStatus::Published, 'published_at' => now()->addWeek()]);

        $this->get('/blog/borrador')->assertNotFound();
        $this->get('/blog/futuro')->assertNotFound();
    }

    public function test_las_urls_viejas_redirigen_permanentemente(): void
    {
        $this->get('/proyects')->assertRedirect('/proyectos')->assertStatus(301);
        $this->get('/blogs')->assertRedirect('/blog')->assertStatus(301);
        $this->get('/clientes')->assertRedirect('/testimonios')->assertStatus(301);
    }

    public function test_el_sitio_funciona_aunque_no_haya_empresa_configurada(): void
    {
        Business::query()->delete();
        cache()->flush();

        $this->get('/')->assertOk();
    }
}

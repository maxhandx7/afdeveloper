<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Pone nombres que se entienden a las tablas de contenido y les agrega lo
 * que les faltaba (slugs, orden, destacados, SEO). No borra ningún dato.
 *
 *  links     → projects
 *  clients   → testimonials   (eran testimonios, no clientes que pagan)
 *  bandejas  → contact_messages
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Proyectos ────────────────────────────────────────────
        Schema::rename('links', 'projects');

        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('repo_url')->nullable()->after('link');
            $table->json('tech_stack')->nullable()->after('long_description');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->unsignedInteger('sort_order')->default(0)->after('is_featured');
        });

        $this->fillSlugs('projects', 'title');

        Schema::table('projects', function (Blueprint $table) {
            $table->unique('slug');
        });

        // Los 3 que se mostraban en el home pasan a ser "destacados".
        DB::table('projects')->where('status', 'ACTIVE')->orderBy('id')->limit(3)
            ->update(['is_featured' => true]);

        // ── Blog ─────────────────────────────────────────────────
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('excerpt', 300)->nullable()->after('slug');
            $table->string('meta_description', 160)->nullable()->after('excerpt');
            $table->timestamp('published_at')->nullable()->after('status');
        });

        $this->fillSlugs('posts', 'title');

        DB::table('posts')->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);

        Schema::table('posts', function (Blueprint $table) {
            $table->unique('slug');
        });

        // ── Testimonios ──────────────────────────────────────────
        Schema::rename('clients', 'testimonials');

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('role')->nullable()->after('name');
            $table->boolean('is_published')->default(true)->after('description');
            $table->unsignedInteger('sort_order')->default(0)->after('is_published');
        });

        // ── Equipo ───────────────────────────────────────────────
        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('rol');
        });

        // ── Mensajes de contacto ─────────────────────────────────
        Schema::rename('bandejas', 'contact_messages');

        Schema::table('contact_messages', function (Blueprint $table) {
            // Antes era string(255): los mensajes largos se cortaban o fallaban.
            $table->text('message')->change();
            $table->string('phone')->nullable()->change();
            $table->timestamp('read_at')->nullable()->after('message');
            $table->string('ip_address', 45)->nullable()->after('read_at');
            $table->string('user_agent')->nullable()->after('ip_address');
        });

        // ── Empresa ──────────────────────────────────────────────
        // El logo se guardaba solo como nombre de archivo dentro de public/image.
        // Ahora se guarda la ruta relativa completa, igual que el resto de imágenes.
        foreach (DB::table('businesses')->get(['id', 'logo']) as $business) {
            if ($business->logo && ! Str::startsWith($business->logo, ['image/', 'images/'])) {
                DB::table('businesses')->where('id', $business->id)
                    ->update(['logo' => 'image/'.$business->logo]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['read_at', 'ip_address', 'user_agent']);
        });
        Schema::rename('contact_messages', 'bandejas');

        Schema::table('teams', fn (Blueprint $table) => $table->dropColumn('sort_order'));

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_published', 'sort_order']);
        });
        Schema::rename('testimonials', 'clients');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'excerpt', 'meta_description', 'published_at']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'repo_url', 'tech_stack', 'is_featured', 'sort_order']);
        });
        Schema::rename('projects', 'links');
    }

    private function fillSlugs(string $table, string $from): void
    {
        $used = [];

        foreach (DB::table($table)->orderBy('id')->get(['id', $from]) as $row) {
            $base = Str::slug($row->{$from}) ?: Str::lower(Str::singular($table));
            $slug = $base;
            $i = 2;

            while (in_array($slug, $used, true)) {
                $slug = "{$base}-{$i}";
                $i++;
            }

            $used[] = $slug;
            DB::table($table)->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};

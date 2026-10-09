<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicDocumentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Webhooks\CrediTrackWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/proyectos', [SiteController::class, 'projects'])->name('projects');
Route::get('/proyectos/{project}', [SiteController::class, 'project'])->name('projects.show');
Route::get('/testimonios', [SiteController::class, 'testimonials'])->name('testimonials');
Route::get('/equipo', [SiteController::class, 'team'])->name('team');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

// Máximo 5 mensajes por minuto por IP: suficiente para humanos, molesto para bots.
Route::post('/contacto', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// PDF de cotizaciones y cuentas de cobro para el cliente (enlace con token, sin login).
Route::get('/documentos/{token}', PublicDocumentController::class)
    ->where('token', '[A-Za-z0-9]{40}')
    ->middleware('throttle:30,1')
    ->name('documents.public');
Route::get('/panel/documentos/{billingDocument}/pdf', [PublicDocumentController::class, 'preview'])
    ->middleware('auth')
    ->name('documents.preview');

// Eventos de CrediTrack (préstamos y pagos) → movimientos en Finanzas. Verificados con firma HMAC.
Route::post('/webhooks/creditrack', CrediTrackWebhookController::class)
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class)
    ->middleware('throttle:60,1')
    ->name('webhooks.creditrack');

// ── Redirecciones de las URLs viejas (para no perder SEO ni enlaces compartidos) ──
Route::permanentRedirect('/proyects', '/proyectos');
Route::permanentRedirect('/blogs', '/blog');
Route::permanentRedirect('/clientes', '/testimonios');
Route::permanentRedirect('/home', '/admin');
Route::permanentRedirect('/login', '/admin/login')->name('login');

<?php

namespace App\Providers;

use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('es');

        // En desarrollo, Eloquent se queja de N+1 y atributos inexistentes
        // en vez de fallar en silencio. En producción no molesta.
        Model::shouldBeStrict(! $this->app->isProduction());

        // Datos de la empresa solo para las vistas públicas, y cacheados.
        // (Antes había 3 service providers consultando la BD en CADA request,
        // incluidos los comandos de artisan.)
        View::composer(['layouts.web', 'pages.*', 'partials.*'], function ($view) {
            $view->with('business', Business::current());
        });
    }
}

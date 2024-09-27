<?php

namespace App\Providers;

use App\Models\Link;
use Illuminate\Cache\RateLimiting\Limit;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class Proyects extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        if (Schema::hasTable('links')) {
            $proyects = link::get();
            if ($proyects->isNotEmpty()) {
                $proyects = Link::where('status', 'ACTIVE')
                    ->limit(3) 
                    ->get();
                view()->share('proyectos', $proyects);
            } else {
                view()->share('proyectos', null);
            }
        } else {
            view()->share('proyectos', null);
        }
        

    }
}

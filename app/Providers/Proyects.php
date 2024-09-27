<?php

namespace App\Providers;

use App\Models\Link;
use Illuminate\Cache\RateLimiting\Limit;
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
        $proyects =  Link::where('status', 'ACTIVE')
        ->Limit('3')
        ->get();
        
        
         view()->share('proyects', $proyects);
    }
}

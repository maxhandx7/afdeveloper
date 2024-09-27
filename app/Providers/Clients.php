<?php

namespace App\Providers;

use App\Models\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class Clients extends ServiceProvider
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
        if (Schema::hasTable('clients')) {
            $clients = Client::get();
            if ($clients->isNotEmpty()) {
                $clients =  Client::Limit('3')
        ->get();
         view()->share('clients', $clients);
            }
        } else {
            view()->share('clients', null);
        }
    }
}

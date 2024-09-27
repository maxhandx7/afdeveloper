<?php

namespace App\Providers;
use App\Models\Business;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;

class Bussiness extends ServiceProvider
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
        if (Schema::hasTable('businesses')) {
            $business = Business::get();
            if ($business->isNotEmpty()) {
                $business = Business::where('id', 1)->firstOrFail();
                view()->share('business', $business);
            }
        } else {
            view()->share('business', null);
        }
    }
}

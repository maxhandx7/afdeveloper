<?php

namespace App\Providers;
use App\Models\Business;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
                $business->shortDescription = Str::limit(strip_tags($business->description), 150);
                view()->share('business', $business);
            }
        } else {
            view()->share('business', null);
        }
    }
}

<?php

namespace App\Providers\v1;

use App\Services\v1\FlightService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class FlightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(FlightService::class, function ($app) {
            return new FlightService();
        });
        
        Validator::extend('flightstatus', function ($attribute, $value,$parameters,$validator){
            return $value == 'ontime' || $value == 'delayed';
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

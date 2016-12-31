<?php

namespace hollanbo\Weather;

use Illuminate\Support\ServiceProvider;
use hollanbo\Weather\Commands\GetWeatherData;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'hollanbo_weather');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GetWeatherData::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('hollanbo\Weather\Controllers\WeatherController');
    }
}

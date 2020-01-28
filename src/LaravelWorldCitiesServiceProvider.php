<?php

namespace PaulinTrognon\LaravelWorldCities;

use Illuminate\Support\ServiceProvider;

class LaravelWorldCitiesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\Download::class,
            ]);
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }
}
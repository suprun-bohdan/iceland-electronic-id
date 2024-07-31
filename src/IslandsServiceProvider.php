<?php

namespace AdvancedSolutions\IcelandElectronicId;

use Illuminate\Support\ServiceProvider;

class IslandsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/islands.php' => config_path('islands.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/Config/islands.php', 'islands'
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(IslandsService::class, function ($app) {
            return new IslandsService($app['config']['islands']);
        });
    }
}

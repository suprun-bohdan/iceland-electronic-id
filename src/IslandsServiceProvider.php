<?php

namespace AdvancedSolutions\IcelandElectronicID;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class IslandsServiceProvider extends ServiceProvider
{
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
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->singleton(IslandsExtendSocialite::class, function ($app) {
            return new IslandsExtendSocialite;
        });

        $this->app->make(IslandsExtendSocialite::class)->handle($this->app['Laravel\Socialite\Contracts\Factory']);
    }
}

<?php

namespace AdvancedSolutions\IcelandElectronicId;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;
use Illuminate\Contracts\Container\BindingResolutionException;

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
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->singleton(IslandsExtendSocialite::class, function ($app) {
            return new IslandsExtendSocialite;
        });

        try {
            $this->app->make(SocialiteFactory::class);
        } catch (BindingResolutionException $e) {
            throw new \RuntimeException('Laravel Socialite is not configured. Please make sure to install and configure laravel/socialite.');
        }

        $this->app->make(IslandsExtendSocialite::class)->handle($this->app['Laravel\Socialite\Contracts\Factory']);
    }
}

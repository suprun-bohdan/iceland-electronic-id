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
            $this->printSocialiteSetupInstructions();
            throw new \RuntimeException('Laravel Socialite is not configured. Please make sure to install and configure laravel/socialite.');
        }

        $this->app->make(IslandsExtendSocialite::class)->handle($this->app[SocialiteFactory::class]);
    }

    /**
     * Print setup instructions for Laravel Socialite.
     *
     * @return void
     */
    protected function printSocialiteSetupInstructions()
    {
        echo "\nPlease follow these steps to install and configure Laravel Socialite:\n";
        echo "1. Install Socialite:\n";
        echo "   composer require laravel/socialite\n\n";
        echo "2. Add the Socialite service provider in config/app.php:\n";
        echo "   'providers' => [\n";
        echo "       // Other service providers...\n";
        echo "       Laravel\Socialite\SocialiteServiceProvider::class,\n";
        echo "   ],\n\n";
        echo "3. Add the Socialite facade in config/app.php:\n";
        echo "   'aliases' => [\n";
        echo "       // Other aliases...\n";
        echo "       'Socialite' => Laravel\Socialite\Facades\Socialite::class,\n";
        echo "   ],\n\n";
        echo "4. Run the command:\n";
        echo "   php artisan config:clear\n\n";
    }
}

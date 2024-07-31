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
            __DIR__ . '/Config/islands.php' => config_path('islands.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/Config/islands.php', 'islands'
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->isSocialiteAvailable()) {
            $this->app->singleton(IslandsExtendSocialite::class, function ($app) {
                return new IslandsExtendSocialite;
            });

            try {
                $this->app->make(IslandsExtendSocialite::class)->handle($this->app[SocialiteFactory::class]);
            } catch (BindingResolutionException $e) {
            }
        }
    }

    /**
     * Check if Laravel Socialite is available.
     *
     * @return bool
     */
    protected function isSocialiteAvailable(): bool
    {
        $config = $this->app['config'];

        // Check if the Socialite service provider is registered
        $providers = $config->get('app.providers');
        if (!in_array(\Laravel\Socialite\SocialiteServiceProvider::class, $providers)) {
            return false;
        }

        $aliases = $config->get('app.aliases');
        if (!array_key_exists('Socialite', $aliases) || $aliases['Socialite'] !== \Laravel\Socialite\Facades\Socialite::class) {
            return false;
        }

        try {
            $this->app->make(SocialiteFactory::class);
        } catch (BindingResolutionException $e) {
            return false;
        }

        return true;
    }
}

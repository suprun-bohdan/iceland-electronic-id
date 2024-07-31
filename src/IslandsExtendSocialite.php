<?php

namespace AdvancedSolutions\IcelandElectronicID;

use Laravel\Socialite\Contracts\Factory;

class IslandsExtendSocialite
{
    public static function handle(Factory $socialite)
    {
        $socialite->extend('islands', function ($app) use ($socialite) {
            $config = $app['config']['services.islands'];
            return $socialite->buildProvider(IslandsProvider::class, $config);
        });
    }
}

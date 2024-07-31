<?php

namespace AdvancedSolutions\IcelandElectronicID;

use GuzzleHttp\ClientInterface;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use Illuminate\Support\Arr;

class IslandsProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopeSeparator = ' ';
    protected $scopes = ['openid', 'profile'];

    protected function getAuthUrl($state)
    {
        $this->with(['state' => $state]);
        return $this->buildAuthUrlFromBase(config('islands.base_url') . '/connect/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return config('islands.base_url') . '/connect/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('islands.base_url') . '/connect/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['sub'],
            'nickname' => Arr::get($user, 'nickname'),
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => Arr::get($user, 'picture'),
        ]);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}

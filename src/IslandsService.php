<?php

namespace AdvancedSolutions\IcelandElectronicId;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class IslandsService
{
    protected array $config;
    protected Client $httpClient;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->httpClient = new Client();
    }

    /**
     * @throws GuzzleException
     */
    public function authenticate($authorizationCode)
    {
        $tokenUrl = $this->config['token_url'];

        try {
            $response = $this->httpClient->post($tokenUrl, [
                'form_params' => [
                    'client_id' => $this->config['client_id'],
                    'client_secret' => $this->config['client_secret'],
                    'redirect_uri' => $this->config['redirect_uri'],
                    'grant_type' => 'authorization_code',
                    'code' => $authorizationCode,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('Authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getUserInfo($accessToken)
    {
        $userInfoUrl = $this->config['user_info_url'];

        try {
            $response = $this->httpClient->get($userInfoUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('Failed to retrieve user info: ' . $e->getMessage());
        }
    }
}

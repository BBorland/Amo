<?php

namespace Sync\Kommo;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use League\OAuth2\Client\Token\AccessToken;

class PreparingTokenToJob
{
    /**
     * @var AmoCRMApiClient
     */
    protected AmoCRMApiClient $apiClient;

    /**
     *
     */
    public function __construct()
    {
        $this->apiClient = new AmoCRMApiClient(
            $integrationId = $_ENV['integrationId'],
            $integrationSecretKey = $_ENV['integrationSecretKey'],
            $integrationRedirectUri = $_ENV['integrationRedirectUri'],
        );
    }

    /**
     * Устанавливает токену base domain
     * @param array $account
     * @param AccessToken $token
     * @return array
     * @throws AmoCRMoAuthApiException
     */
    public function preparingTokenToJob(array $account, AccessToken $token)
    {
        $accessToken = $this->apiClient
            ->getOAuthClient()
            ->setBaseDomain($account['base_domain'])
            ->getAccessTokenByRefreshToken($token);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($account['base_domain']);
        return [
            'access_token' => $accessToken->getToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
            'base_domain' => $this->apiClient->getAccountBaseDomain(),
        ];
    }
}
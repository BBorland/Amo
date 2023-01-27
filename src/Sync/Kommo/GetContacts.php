<?php

namespace Sync\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;

class GetContacts extends AuthService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function getCont($name): array
    {
        $accessToken = $this->readToken($name);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['base_domain'])
            ->onAccessTokenRefresh(
                function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'access_token' => $accessToken->getToken(),
                            'refresh_token' => $accessToken->getRefreshToken(),
                            'expires' => $accessToken->getExpires(),
                            'base_domain' => $baseDomain,
                        ]
                    );
                }
            );
        return $this->apiClient->contacts()->get()->toArray();
    }
}

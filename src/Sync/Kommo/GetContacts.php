<?php

namespace Sync\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use mysql_xdevapi\Exception;

class GetContacts extends AuthService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $name
     * @return array
     */
    public function getCont(string $name): array
    {
        $accessToken = $this->readToken($name);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
//            ->onAccessTokenRefresh(
//                function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
//                    saveToken(
//                        [
//                            'access_token' => $accessToken->getToken(),
//                            'refresh_token' => $accessToken->getRefreshToken(),
//                            'expires' => $accessToken->getExpires(),
//                            'base_domain' => $baseDomain,
//                        ]
//                    );
//                }
//            );
        try {
            $contactsArray = $this->apiClient->contacts()->get()->toArray();
        } catch (AmoCRMMissedTokenException | AmoCRMApiException | AmoCRMoAuthApiException) {
            (new AuthService())->auth();
        } catch (Exception $e) {
            echo 'You have a' . $e . 'exception';
            die;
        }
        return $contactsArray;
    }
}

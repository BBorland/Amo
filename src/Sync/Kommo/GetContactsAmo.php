<?php

namespace Sync\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Account;

class GetContactsAmo extends AuthService
{
    /**
     * @param string $name
     * @return array
     */
    public function getCont(string $name): array
    {
        $token = $this->checkAuthToken($name);
        try {
            $contactsArray = $this->apiClient->contacts()->get()->toArray();
        } catch (AmoCRMApiNoContentException $e) {
            echo 'у пользователя нет контактов';
        } catch (AmoCRMApiException $e) {
            (new AuthService())->auth();
        }
        return $contactsArray;
    }

    /**
     * @param string $name
     * @return array
     */
    public function getId(string $name): array
    {
        $token = $this->checkAuthToken($name);
        try {
            $accountArray = $this->apiClient->account()->getCurrent()->toArray();
        } catch (AmoCRMApiException $e) {
            echo 'Error:' . $e->getMessage();
        }
        return $accountArray;
    }

    /**
     * @param array $array
     * @return array
     */
    public function makeArray(array $array): array
    {
        $goodReturn = [];
        foreach ($array as $key => $value) {
            if (isset($value['custom_fields_values'])) {
                foreach ($value['custom_fields_values'] as $key3 => $field3) {
                    if ($field3['field_code'] == 'EMAIL') {
                        foreach ($field3['values'] as $key4 => $field4) {
                            if ($field4['enum_code'] == 'WORK') {
                                $goodReturn[] = [$field4['value'], $value['name']];
                            }
                        }
                    }
                }
            }
        }
        return $goodReturn;
    }


    /**
     * @param $name
     * @return AccessToken
     */
    public function checkAuthToken($name): AccessToken
    {
        if (//!isset(json_decode(file_get_contents('./tokens.json'), true)[$name]) or
        !(Account::where('account_name', $name)->exists())
        ) {
            (new AuthService())->auth();
        }
        $accessToken = $this->readToken($name);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
        return $accessToken;
    }
}

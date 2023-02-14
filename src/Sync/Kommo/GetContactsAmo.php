<?php

namespace Sync\Kommo;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\OAuth2\Client\Provider\AmoCRMException;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Account;

class GetContactsAmo extends AuthService
{
    /**
     * Получет контакты аккаунта
     *
     * @param string $name
     * @return array
     */
    public function getCont(string $name): array
    {
        $token = $this->checkAuthToken($name);
        $contactsArray = [];
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
     * Получение данных об аккаунте
     *
     * @param string $name
     * @return array
     */
    public function getId(string $name): array
    {
        $token = $this->checkAuthToken($name);
        $accountArray = [];
        try {
            $accountArray = $this->apiClient->account()->getCurrent()->toArray();
        } catch (AmoCRMApiException $e) {
            echo 'Error:' . $e->getMessage();
        }
        return $accountArray;
    }

    /**
     * Сортирует массив для Unisender
     *
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
     * Проверяет токен
     *
     * @param string $name
     * @return AccessToken
     */
    public function checkAuthToken(string $name): AccessToken
    {
        if (//!isset(json_decode(file_get_contents('./tokens.json'), true)[$name]) or
        !(Account::where('account_name', $name)->exists())
        ) {
            (new AuthService())->auth();
        }
        $accessToken = $this->readToken($name);
        try {
            $this->apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
        } catch (AmoCRMException $e) {
            exit($e->getMessage());
        }
        return $accessToken;
    }
}

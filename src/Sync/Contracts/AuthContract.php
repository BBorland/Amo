<?php

namespace Sync\Contracts;

use League\OAuth2\Client\Token\AccessToken;

interface AuthContract
{
    /**
     * Сохранение токена авторизации в файле по имени аккаунта в корне проекта.
     *
     * @param array $token
     * @return void
     */
    public function saveAuth(array $token): void;

    /**
     * Получение токена из файла по имени.
     *
     * @param string $accountName
     * @return AccessToken
     */
    public function getAuth(string $accountName): AccessToken;
}

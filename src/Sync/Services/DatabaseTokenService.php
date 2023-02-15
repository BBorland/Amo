<?php

namespace Sync\Services;

use League\OAuth2\Client\Token\AccessToken;
use Sync\Contracts\AuthContract;
use Sync\Core\Controllers\AccountController;
use Sync\Models\Account;

class DatabaseTokenService implements AuthContract
{
    /**
     * @inheritDoc
     */
    public function saveAuth(array $token): void
    {
        Account::updateOrCreate([
            'account_name' => $_SESSION['name']
        ], [
            'token' => json_encode($token, JSON_PRETTY_PRINT)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getAuth(string $accountName): AccessToken
    {
        return new AccessToken(
            (new AccountController())->accountGetToken($accountName)->jsonSerialize()
        );
    }

    public function getAll(): array
    {
        return (new AccountController())->getAllAccounts();
    }

    public function updateToken($array): void
    {
        (new AccountController())->accountUpdate($array['token']);
    }
}

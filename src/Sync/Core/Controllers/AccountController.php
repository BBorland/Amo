<?php

namespace Sync\Core\Controllers;

use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Exception;
use Sync\Models\Account;

class AccountController extends BaseController
{
    /**
     * @param array $data
     * @return void
     */
    public function accountCreate(array $data): void
    {
        Account::updateOrCreate(['account_name' => $data['account_name']],
            [
                'account_id' => $data['account_id'],
                'token' => $data['token'],
                'unisender_key' => $data['unisender_key']
            ]);
    }

    /**
     * @param string $accountName
     * @return AccessToken
     */
    public function accountGetToken(string $accountName): AccessToken
    {
        $token = json_decode(Account::where('account_name', $accountName)->first()->token, true);
        return new AccessToken(
            $token
        );
    }

    /**
     * @param array $data
     * @return void
     */
    public function uniTokenInsert(array $data): void
    {
        Account::updateOrCreate(['account_name' => $data['Uname']],
            [
                'unisender_key' => $data['token']
            ]);
    }
}
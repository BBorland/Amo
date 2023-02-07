<?php

namespace Sync\Core\Controllers;

use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Account;

class AccountController extends BaseController
{
    public function accountCreate(array $data): void
    {
            Account::updateOrCreate(['account_name' => $data['account_name']],
                    ['enum_code' => $data['enum_code'],
                        'token' => $data['token'],
                    'unisender_key' => $data['unisender_key']
                    ]);
    }

    public function accountGetToken($accountName): AccessToken
    {
        return new AccessToken(
            json_decode(Account::where('account_name', $accountName)->first()->token, true)
        );
    }
}
<?php

namespace Sync\Core\Controllers;

use League\OAuth2\Client\Token\AccessToken;
use Sync\Models\Account;

class AccountController extends BaseController
{
    public function accountCreate(array $data): void // TODO: PHPDocs + PSR
    {
            Account::updateOrCreate(['account_name' => $data['account_name']],
                    ['account_id' => $data['account_id'],
                        'token' => $data['token'],
                    'unisender_key' => $data['unisender_key']
                    ]);
    }

    public function accountGetToken($accountName): AccessToken // TODO: PHPDocs
    {
        return new AccessToken( // TODO: если такого имени нет - будет Exception на first()
            json_decode(Account::where('account_name', $accountName)->first()->token, true)
        );
    }

    public function uniTokenInsert(array $data): void // TODO: PHPDocs + PSR
    {
        Account::updateOrCreate(['account_name' => $data['Uname']],
            [
                'unisender_key' => $data['token']
            ]);
    }
}
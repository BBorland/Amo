<?php

namespace Sync\Core\Controllers;

use Illuminate\Database\QueryException;
use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Exception;
use Sync\Models\Account;

class AccountController extends BaseController
{
    /**
     * Создает или обновляет запись в бд
     * @param array $data
     * @return void
     * @throws QueryException
     */
    public function accountCreate(array $data): void
    {
        Account::updateOrCreate([
            'account_name' => $data['account_name']
        ], [
            'account_id' => $data['account_id'],
            'token' => $data['token'],
            'unisender_key' => $data['unisender_key'],
        ]);
    }

    /**
     * @param string $accountName
     * @return AccessToken
     */
    public function accountGetToken(string $accountName): AccessToken
    {
        try {
            $token = json_decode(Account::where('account_name', $accountName)->first()->token, true);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
        return new AccessToken(
            $token
        );
    }

    /**
     * Добавляет в таблицу токен
     * @param array $data
     * @return void
     * @throws QueryException
     */
    public function uniTokenInsert(array $data): void
    {
        Account::updateOrCreate([
            'account_name' => $data['Uname']
        ], [
            'unisender_key' => $data['token']
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function enumInsert(array $data): void
    {
        Account::updateOrCreate([
            'account_name' => $data['account_name']
        ], [
            'enum' => $data['enum']
        ]);
    }

    /**
     * @param string $accountId
     * @return string
     */
    public function accountGetEnum(string $accountId): string
    {
        return Account::where('account_id', $accountId)->first()->enum;
    }

    /**
     * @return array
     */
    public function getAllAccounts(): array
    {
        return Account::all()->toArray();
    }

    /**
     * @param string $token
     * @return void
     */
    public function accountUpdate(string $token): void
    {
        $users = Account::all();
        foreach ($users as $user) {
            $user->token = $token;
            $user->save();
        }
    }

    /**
     * @param string $name
     * @return array
     */
    public function getOneAccount(string $name)
    {
        return Account::query()->where('account_name', $name)->first()->toArray();
    }
}

<?php

namespace Sync\Services;

use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Exception;
use Sync\Contracts\AuthContract;
use Sync\Core\Controllers\AccountController;

class LocalTokenService implements AuthContract
{
    /** @var string Файл хранения токенов. */
    private const TOKENS_FILE = './tokens.json';

    /**
     * @inheritDoc
     */
    public function saveAuth(array $token): void
    {
        $tokens = file_exists(self::TOKENS_FILE)
            ? json_decode(file_get_contents(self::TOKENS_FILE), true)
            : [];
        $tokens[$_SESSION['name']] = $token;
        file_put_contents(self::TOKENS_FILE, json_encode($tokens, JSON_PRETTY_PRINT));
    }


    /**
     * @inheritDoc
     */
    public function getAuth(string $accountName): AccessToken
    {
        try {
            $token = json_decode(file_get_contents(self::TOKENS_FILE), true)[$accountName];
        } catch (Exception $e) {
            exit($e->getMessage());
        }
        return new AccessToken(
            $token
        );
    }

    public function getAll(): array
    {
        return json_decode(file_get_contents('./tokens.json'), true);
    }

    public function updateToken($array): void
    {
        file_put_contents('./tokens.json', '');
        $tokens = [];
        foreach ($array['names'] as $name) {
            $tokens[$name] = json_decode($array['token'], true);
        }
        file_put_contents('./tokens.json', json_encode($tokens, JSON_PRETTY_PRINT));
    }
}

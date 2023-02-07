<?php

namespace Sync\Kommo;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use League\OAuth2\Client\Token\AccessToken;
use Sync\Core\Controllers\AccountController;
use Sync\Core\Controllers\BaseController;
use Sync\Models\Account;

class AuthService extends BaseController
{
    /** @var string Базовый домен авторизации. */
    private const TARGET_DOMAIN = 'kommo.com';

    /** @var string Файл хранения токенов. */
    private const TOKENS_FILE = './tokens.json'; // TODO: уже не нужна эта константа, работаем с базой

    /** @var AmoCRMApiClient AmoCRM клиент. */
    protected AmoCRMApiClient $apiClient;

    public function __construct()
    {
        parent::__construct();
        $this->apiClient = new AmoCRMApiClient( //TODO: эти данные нужно вынести в файл конфигураций
            $integrationId = 'b01aea71-d988-499a-83d1-7ce7059a51ad',
            $integrationSecretKey = 'ngP8ZecdxiSKTFpa6yerT2G5iWCt4egmCfKe3slTQcjB9acmZpLkUpb4bTNPALxh',
            $integrationRedirectUri = 'https://cc37-212-46-197-210.eu.ngrok.io/auth',
        );
    }

    /**
     * Авторизация.
     *
     * @return string
     */
    public function auth(): string
    {
        session_start();

        if (isset($_GET['name'])) {
            $_SESSION['name'] = $_GET['name'];
        }

        if (isset($_GET['referer'])) {
            $this
                ->apiClient
                ->setAccountBaseDomain($_GET['referer'])
                ->getOAuthClient()
                ->setBaseDomain($_GET['referer']);
        }

        try {
            if (!isset($_GET['code'])) {
                $state = bin2hex(random_bytes(16));
                $_SESSION['oauth2state'] = $state;
                if (isset($_GET['button'])) {
                    echo $this
                        ->apiClient
                        ->getOAuthClient()
                        ->setBaseDomain(self::TARGET_DOMAIN)
                        ->getOAuthButton([
                            'title' => 'Установить интеграцию',
                            'compact' => true,
                            'class_name' => 'className',
                            'color' => 'default',
                            'error_callback' => 'handleOauthError',
                            'state' => $state,
                        ]);
                } else {
                    $authorizationUrl = $this
                        ->apiClient
                        ->getOAuthClient()
                        ->setBaseDomain(self::TARGET_DOMAIN)
                        ->getAuthorizeUrl([
                            'state' => $state,
                            'mode' => 'post_message',
                        ]);
                    header('Location: ' . $authorizationUrl);
                }
                die;
            } elseif (empty($_GET['state']) ||
                empty($_SESSION['oauth2state']) ||
                ($_GET['state'] !== $_SESSION['oauth2state'])
            ) {
                unset($_SESSION['oauth2state']);
                exit('Invalid state');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        try {
            $accessToken = $this
                ->apiClient
                ->getOAuthClient()
                ->setBaseDomain($_GET['referer'])
                ->getAccessTokenByCode($_GET['code']);
            if (!$accessToken->hasExpired()) {
                $this->saveToken([
                    'access_token' => $accessToken->getToken(),
                    'refresh_token' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'base_domain' => $this->apiClient->getAccountBaseDomain(),
                ]);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $_SESSION['name'];
    }

    /**
     * Сохранение токена авторизации по имени аккаунта.
     *
     * @param array $token
     * @return void
     */
    private function saveToken(array $token): void
    {
        $tokens = (Account::where('account_name', $_SESSION['name'])->exists())
            ? (new AccountController())->accountGetToken($_SESSION['name'])
            : [];
        $tokens = file_exists(self::TOKENS_FILE) // TODO: снова файлы, их не должно быть, только база
            ? json_decode(file_get_contents(self::TOKENS_FILE), true)
            : [];
        $tokens[$_SESSION['name']] = $token;
        Account::updateOrCreate(['account_name' => $_SESSION['name']], // TODO: PSR
            ['token' => json_encode($token, JSON_PRETTY_PRINT)]);
        file_put_contents(self::TOKENS_FILE, json_encode($tokens, JSON_PRETTY_PRINT)); // TODO: файлы
    }

    /**
     * Получение токена из файла по имени.
     *
     * @param string $accountName
     * @return AccessToken
     */
    public function readToken(string $accountName): AccessToken
    {
        return new AccessToken( // TODO: тоже файлы, нужна только база
            json_decode(file_get_contents(self::TOKENS_FILE), true)[$accountName]
        );
    }
}
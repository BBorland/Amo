<?php

namespace Sync\Kommo;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use Symfony\Component\Dotenv\Dotenv;
use Sync\Contracts\AuthContract;
use Sync\Core\Controllers\BaseController;
use Sync\Services\LocalTokenService;

class ApiClient extends BaseController
{
    /** @var AuthContract Сервис работы с токеном авторизации. */
    public AuthContract $authService;

    /** @var string Базовый домен авторизации. */
    private const TARGET_DOMAIN = 'kommo.com';

    /** @var AmoCRMApiClient AmoCRM клиент. */
    protected AmoCRMApiClient $apiClient;

    public function __construct()
    {
        parent::__construct();
        $dotenv = new Dotenv();
        $dotenv->load('./.env');
        $this->apiClient = new AmoCRMApiClient(
            $integrationId = $_ENV['integrationId'],
            $integrationSecretKey = $_ENV['integrationSecretKey'],
            $integrationRedirectUri = $_ENV['integrationRedirectUri'],
        );

        /** Если требуется сохранять токен локально. */
        $this->authService = new LocalTokenService();

        /** Если требуется сохранять токен в БД. */
//         $this->authService = new DatabaseTokenService();
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
            } elseif (
                empty($_GET['state']) ||
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
                $this->authService->saveAuth([
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
}

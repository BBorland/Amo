<?php

namespace Sync\Command;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Carbon\Carbon;
use League\OAuth2\Client\Token\AccessToken;
use Pheanstalk\Pheanstalk;
use \Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Sync\Core\Controllers\AccountController;

class TokenUpdate extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'token-update';

    protected AmoCRMApiClient $apiClient;

    protected AccessToken $accessToken;

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
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('token-update');
        $this->addOption(
            'time',
            't',
            InputOption::VALUE_OPTIONAL,
            'Введите кол-во часов, и все токены, которые истекают через время меньше введенного будут обновлены',
            24
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws AmoCRMoAuthApiException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arrayAllAccounts = (new AccountController())->getAllAccounts();
        $timeToUpdate = $input->getOption('time');
        if (preg_match('/^[\d]+$/',$timeToUpdate) != 0)
            $timeToUpdate = (int) $timeToUpdate;
        else
            exit('Ошибка ввода' . PHP_EOL);
        foreach ($arrayAllAccounts as $account) {
            $token = (new AccountController())->accountGetToken($account['account_name']);
            $expires = Carbon::createFromTimestamp((json_decode($account['token'], true))['expires']);
            if (Carbon::now()->diffInHours($expires) < $timeToUpdate) {
                $accessToken = $this->apiClient
                    ->getOAuthClient()
                    ->setBaseDomain((json_decode($account['token'], true))['base_domain'])
                    ->getAccessTokenByRefreshToken($token);
                $this->apiClient->setAccessToken($accessToken)
                    ->setAccountBaseDomain((json_decode($account['token'], true))['base_domain']);
                $array = [
                    'access_token' => $accessToken->getToken(),
                    'refresh_token' => $accessToken->getRefreshToken(),
                    'expires' => $accessToken->getExpires(),
                    'base_domain' => $this->apiClient->getAccountBaseDomain(),
                ];
                $job = Pheanstalk::create('127.0.0.1')
                    ->useTube('refresh')
                    ->put(json_encode([$account['account_name'], 'token' => json_encode($array)]), JSON_PRETTY_PRINT);
            }
            exit();
        }
        return 0;
    }
}
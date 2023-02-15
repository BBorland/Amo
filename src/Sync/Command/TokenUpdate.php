<?php

namespace Sync\Command;

use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use League\OAuth2\Client\Token\AccessToken;
use Pheanstalk\Pheanstalk;
use \Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;
use Sync\Config\BeanstalkConfig;
use Sync\Kommo\FindTheSmallestTimeToUpdate;
use Sync\Kommo\PreparingTokenToJob;
use Sync\Kommo\ApiClient;

class TokenUpdate extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'token-update';

    /**
     * @var AccessToken
     */
    protected AccessToken $accessToken;

    /**
     * @var Pheanstalk
     */
    protected Pheanstalk $connection;

    /**
     *
     */
    public function __construct(BeanstalkConfig $beanstalk)
    {
        parent::__construct();
        $dotenv = new Dotenv();
        $dotenv->load('./.env');
        $this->connection = $beanstalk->getConnection();
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
     * Отправляет сообщение в очередь
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws AmoCRMoAuthApiException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $arrayAllAccounts = (new ApiClient())->authService->getAll();
        $timeToUpdate = $input->getOption('time');
        $arrayOfTimeToUpdate = (new FindTheSmallestTimeToUpdate())
            ->findTheSmallestTimeToUpdate($arrayAllAccounts, $timeToUpdate, true);
        if (!empty($arrayOfTimeToUpdate)) {
            $name = key($arrayOfTimeToUpdate[0]);
            $account = $arrayAllAccounts[$name];
            $token = (new ApiClient())->authService->getAuth($name);
            $array = (new PreparingTokenToJob())
                ->preparingTokenToJob($account, $token);
            $job = $this->connection
                ->useTube('refresh')
                ->put(
                    json_encode([
                        'name' => $name,
                        'token' => json_encode($array),
                        'names' => array_keys($arrayAllAccounts)
                    ]),
                    JSON_PRETTY_PRINT
                );
        }
        return 0;
    }
}

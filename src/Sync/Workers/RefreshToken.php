<?php

namespace Sync\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\Core\Controllers\AccountController;
use Sync\Kommo\AuthService;
use Sync\Kommo\GetContactsAmo;
use Sync\Models\Account;
use Throwable;

class RefreshToken extends AbstractWorker
{
    /**
     * @var string
     */
    protected static $defaultName = 'refresh-token';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('refresh-token');
    }

    /**
     * @var string
     */
    protected string $queue = 'refresh';

    /**
     * Отправляет запрос на обновление токенов
     * @param $data
     */
    public function process($data): void
    {
        $array = json_decode($data, true);
        (new AccountController())->accountUpdate($array['token']);
    }
}

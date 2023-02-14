<?php

namespace Sync\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class TimeWorker extends AbstractWorker
{
    /**
     * @var string
     */
    protected static $defaultName = 'async-worker';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('async-worker');
    }

    /**
     * @var string
     */
    protected string $queue = 'times';

    /**
     * Выводит время
     * @param $data
     * @return void
     */
    public function process($data): void
    {
        echo $data . PHP_EOL;
    }
}

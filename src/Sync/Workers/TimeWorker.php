<?php

namespace Sync\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class TimeWorker extends \Symfony\Component\Console\Command\Command
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
     * @var Pheanstalk
     */
    protected Pheanstalk $connection;

    /**
     * @var string
     */
    protected string $queue = 'times';

    /**
     *
     */
    final public function __construct()
    {
        parent::__construct();
        $this->connection = Pheanstalk::create('127.0.0.1');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        while ($job = $this->connection
            ->watchOnly($this->queue)
            ->ignore(PheanstalkInterface::DEFAULT_TUBE)
            ->reserve()
        ) {
            try {
                $this->process($job->getData());
            } catch (Throwable $exception) {
                exit($exception->getMessage());
            }
            $this->connection->delete($job);
        }
    }

    /**
     * @param $data
     * @return void
     */
    public function process($data): void
    {
        echo $data . PHP_EOL;
    }
}
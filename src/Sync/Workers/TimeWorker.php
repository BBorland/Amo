<?php

namespace Sync\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Throwable;

class TimeWorker
{
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
        $this->connection = Pheanstalk::create('127.0.0.1');
    }

    /**
     * @return void
     */
    public function execute(): void
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
        exit();
    }
}
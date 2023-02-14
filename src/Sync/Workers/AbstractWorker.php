<?php

namespace Sync\Workers;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job;
use Pheanstalk\Pheanstalk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sync\config\BeanstalkConfig;
use Throwable;

/**
 *
 */
abstract class AbstractWorker extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var Pheanstalk
     */
    protected Pheanstalk $connection;

    /**
     * @var string
     */
    protected string $queue = 'default';

    /**
     *
     */
    final public function __construct(BeanstalkConfig $beanstalk)
    {
        parent::__construct();
        $this->connection = $beanstalk->getConnection();
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
                $this->connection->delete($job);
            } catch (Throwable $exception) {
                $this->handleException($exception, $job);
            }
        }
    }

    /**
     * @param Throwable $exception
     * @param Job $job
     * @return void
     */
    private function handleException(Throwable $exception, Job $job): void
    {
        echo "Error exception $exception" . PHP_EOL . $job->getData();
    }

    /**
     * @param $data
     * @return void
     */
    abstract public function process($data): void;
}

<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Workers\TimeWorker;

class TimeWorkerFactory
{
    /**
     * @param ContainerInterface $container
     * @return TimeWorker
     */
    public function __invoke(ContainerInterface $container): TimeWorker
    {
        return new TimeWorker((new BeanstalkConfig($container)));
    }
}

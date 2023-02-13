<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Workers\TimeWorker;

class TimeWorkerFactory
{
    public function __invoke(ContainerInterface $container): TimeWorker
    {
        return new TimeWorker((new BeanstalkConfig($container)));
    }
}
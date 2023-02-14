<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Sync\Command\TimeCommand;
use Sync\Config\BeanstalkConfig;

class TimeCommandFactory
{
    /**
     * @param ContainerInterface $container
     * @return TimeCommand
     */
    public function __invoke(ContainerInterface $container): TimeCommand
    {
        return new TimeCommand((new BeanstalkConfig($container)));
    }
}
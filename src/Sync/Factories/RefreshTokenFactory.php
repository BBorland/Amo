<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Sync\Config\BeanstalkConfig;
use Sync\Workers\RefreshToken;
use Sync\Workers\TimeWorker;

class RefreshTokenFactory
{
    /**
     * @param ContainerInterface $container
     * @return RefreshToken
     */
    public function __invoke(ContainerInterface $container): RefreshToken
    {
        return new RefreshToken((new BeanstalkConfig($container)));
    }
}

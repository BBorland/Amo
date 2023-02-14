<?php

namespace Sync\Factories;

use Psr\Container\ContainerInterface;
use Sync\Command\TokenUpdate;
use Sync\Config\BeanstalkConfig;

class TokenUpdateFactory
{
    /**
     * @param ContainerInterface $container
     * @return TokenUpdate
     */
    public function __invoke(ContainerInterface $container): TokenUpdate
    {
        return new TokenUpdate((new BeanstalkConfig($container)));
    }
}
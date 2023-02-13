<?php

namespace Sync\Config;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class BeanstalkConfig
{
    /**
     * @var Pheanstalk|null
     */
    private ?Pheanstalk $connection;

    /**
     * @var array|mixed
     */
    private array $config;

    /**
     *
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            $this->config = $container->get('config')['beanstalk'];
            $this->connection = Pheanstalk::create(
                $this->config['host'],
                $this->config['port'],
                $this->config['timeout'],
            );
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            exit($e->getMessage());
        }
    }

    /**
     * @return Pheanstalk|null
     */
    public function getConnection(): ?Pheanstalk
    {
        return $this->connection;
    }
}

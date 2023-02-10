<?php

namespace Sync\config;

use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class BeanstalkConfig
{
    /**
     * @var Pheanstalk|null
     */
    private ?Pheanstalk $connection;

    /**
     *
     */
    public function __construct()
    {
        try {
            $config = (include './config/autoload/beanstalk.php')['beanstalk'];
            $this->connection = Pheanstalk::create(
                $config['host'],
                $config['port'],
                $config['timeout'],
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
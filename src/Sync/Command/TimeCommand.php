<?php

namespace Sync\Command;

use Pheanstalk\Pheanstalk;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Sync\Config\BeanstalkConfig;

class TimeCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'how-time';

    /**
     * @return void
     */

    /**
     * @var Pheanstalk
     */
    protected Pheanstalk $connection;

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('how-time');
    }

    /**
     * @param BeanstalkConfig $beanstalk
     */
    public function __construct(BeanstalkConfig $beanstalk)
    {
        parent::__construct();
        $this->connection = $beanstalk->getConnection();
    }

    /**
     * Отправляет сообщение в очередь
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        date_default_timezone_set('Europe/Moscow');
        $currentTime = "Now time: " . date("H:i (m.Y)");
        $job = $this->connection
            ->useTube('times')
            ->put(json_encode($currentTime));
        return 0;
    }
}

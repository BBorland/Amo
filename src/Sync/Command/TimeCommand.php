<?php

namespace Sync\Command;

require '/home/osboxes/Desktop/project/mezzio/vendor/autoload.php';

use Pheanstalk\Pheanstalk;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use Sync\Workers\TimeWorker;

class TimeCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'how-time';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('how-time');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        date_default_timezone_set('Europe/Moscow');
        $currentTime = "Now time: " . date("H:i (m.Y)");
        $job = Pheanstalk::create('127.0.0.1')
            ->useTube('times')
            ->put(json_encode($currentTime));
        return 0;
    }
}
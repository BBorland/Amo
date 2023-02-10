<?php

namespace Sync\Command;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

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
        $currentTime = date("H:i (m.Y)");
        $output->writeln("Now time: " . $currentTime);
        return 0;
    }
}
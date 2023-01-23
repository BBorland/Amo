<?php

declare(strict_types=1);

namespace Sync;

use Sync\Factories\TestHandlerFactory;
use Sync\Handlers\TestHandler;
use Sync\Factories\SummerHandlerFactory;
use Sync\Handlers\SummerHandler;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() :array
    {
        return [
            'invokables' => [
            ],
	        'factories' => [
                \Sync\Handlers\SummerHandler::class => \Sync\Factories\SummerHandlerFactory::class,
	        ],
        ];
    }
}
 
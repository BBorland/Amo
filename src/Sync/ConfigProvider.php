<?php

declare(strict_types=1);

namespace Sync;

use Sync\Factories\TestHandlerFactory;
use Sync\Handlers\TestHandler;
use Sync\Factories\SummerHandlerFactory;
use Sync\Handlers\SummerHandler;
use Sync\Handlers\AuthHandler;
use Sync\Factories\AuthHandlerFactory;

class ConfigProvider
{
    /**
     * @return array[]
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories' => [
                \Sync\Handlers\SummerHandler::class => \Sync\Factories\SummerHandlerFactory::class,
                \Sync\Handlers\AuthHandler::class => \Sync\Factories\AuthHandlerFactory::class,
                \Sync\Handlers\GetContactsAmoHandler::class => \Sync\Factories\GetContactsAmoHandlerFactory::class,
                \Sync\Handlers\GetContactsUniHandler::class => \Sync\Factories\GetContactsUniHandlerFactory::class,
                \Sync\Handlers\ImportAmoToUniHandler::class => \Sync\Factories\ImportAmoToUniHandlerFactory::class,
                \Sync\Handlers\ImportAmoToDbHandler::class => \Sync\Factories\ImportAmoToDbHandlerFactory::class,
                \Sync\Handlers\GetUniTokenHandler::class => \Sync\Factories\GetUniTokenHandlerFactory::class,
                \Sync\Handlers\WebHookHandler::class => \Sync\Factories\WebHookHandlerFactory::class,
                \Sync\Handlers\ContactsToDbHandler::class => \Sync\Factories\ContactsToDbHandlerFactory::class,
                \Sync\Workers\TimeWorker::class => \Sync\Factories\TimeWorkerFactory::class,
                \Sync\Workers\RefreshToken::class => \Sync\Factories\RefreshTokenFactory::class,
            ],
        ];
    }
}

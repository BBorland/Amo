<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;

return [
    // Toggle the configuration cache. Set this to boolean false, or remove the
    // directive, to disable configuration caching. Toggling development mode
    // will also disable it by default; clear the configuration cache using
    // `composer clear-config-cache`.
    ConfigAggregator::ENABLE_CACHE => true,

    // Enable debugging; typically used to provide debugging information within templates.
    'debug'  => false,
    'mezzio' => [
        // Provide templates for the error handling middleware to use when
        // generating responses.
        'error_handler' => [
            'template_404'   => 'error::404',
            'template_error' => 'error::error',
        ],
    ],
    'laminas-cli' => [
        'commands' => [
            'how-time' => Sync\Command\TimeCommand::class,
            'async-worker' => Sync\Workers\TimeWorker::class,
            'token-update' => Sync\Command\TokenUpdate::class,
            'refresh-token' => Sync\Workers\RefreshToken::class,
        ],
    ],
];

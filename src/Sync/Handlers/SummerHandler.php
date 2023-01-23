<?php

declare(strict_types=1);

namespace Sync\Handlers;

use Hopex\Simplog\Logger;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SummerHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        date_default_timezone_set('Europe/Moscow');
        $a = $request->getQueryParams();
        $sum = 0;
        foreach ($a as $b => $value) {
            $sum += (int)$value;
        }

        (new Logger())
            ->setLevel(date("Y-m-d"))
            ->setFileName('requests')
            ->custom(array_merge($a, ['sum:' => $sum]));
        return new JsonResponse([
            $sum
            ]
        );
    }
}

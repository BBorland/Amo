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
        $a = $request->getQueryParams();
        $sum = 0;
        $log = new Logger();
        $answer = [];
        $log->setLevel('./2025-11-15');
        $log->setFileName('/requests.log');
        foreach ($a as $b => $value) {
            $sum += (int)$value;
            $answer[$b] = $value;
        }
        $answer["sum"] = $sum;
        $log->custom([$answer]);
        return new JsonResponse([
            $sum
            ]
        );
    }
}

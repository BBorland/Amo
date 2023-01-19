<?php

declare(strict_types=1);

namespace Sync\Handlers;

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
        foreach ($a as $b => $value) {
            $sum += (int)$value;
        }
        return new JsonResponse([
                $sum
            ]
        );
    }
}

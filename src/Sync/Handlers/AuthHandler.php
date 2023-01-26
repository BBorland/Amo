<?php

declare(strict_types=1);

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AuthService;

class AuthHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['name'];
        if (!isset(json_decode(file_get_contents('./tokens.json'), true)[$a])) {
            (new AuthService())->auth();
//            return new JsonResponse([
//                ['name' => $_SESSION['name']]
//            ]);
        }
        $access_token = json_decode(file_get_contents('./tokens.json'), true)[$a]['access_token'];
        return new JsonResponse([
//            ['name' => $a]
            json_decode(file_get_contents('./tokens.json'), true)[$a]['access_token']
        ]);
    }
}

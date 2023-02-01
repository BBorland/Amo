<?php

declare(strict_types=1);

namespace Sync\Handlers;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AuthService;

class AuthHandler extends AuthService implements RequestHandlerInterface
{
    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['name'];
        if (!isset($a)) {
            exit('Нету имени');
        }
        if (!isset(json_decode(file_get_contents('./tokens.json'), true)[$a])) {
            (new AuthService())->auth();
            return new JsonResponse([
                ['name' => $_SESSION['name']]
            ]);
        }
        return new JsonResponse([
            ['name' => $a]
        ]);
    }
}

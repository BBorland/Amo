<?php

declare(strict_types=1);

namespace Sync\Handlers;

use AmoCRM\Client\AmoCRMApiClient;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;
use Sync\Kommo\AuthService;

class AuthHandler extends AuthService implements RequestHandlerInterface
{
    /** @var array  */
    private array $connection;

    public function __construct(array $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        (new AccountController($this->connection))->accountCreate([]);
        $a = $request->getQueryParams()['name'];
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

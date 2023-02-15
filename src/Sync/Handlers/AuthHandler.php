<?php

declare(strict_types=1);

namespace Sync\Handlers;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AddWebHook;
use Sync\Kommo\ApiClient;

class AuthHandler extends ApiClient implements RequestHandlerInterface
{
    /** @var array */
    private array $connection;

    /**
     * @param array $connection
     */
    public function __construct(array $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    /**
     * Авторизация
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['name'];
        if ((new ApiClient())->authService->getAuth($a) == null) {
            $name = (new AuthService())->auth();
            $accessToken = $this->authService->getAuth($name);
            $this->apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
            (new AddWebHook())->AddWebHook($this->apiClient);
            return new JsonResponse([
                ['name' => $name]
            ]);
        } else {
            $accessToken = $this->authService->getAuth($a);
            $this->apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
            return new JsonResponse([
                ['name' => $a]
            ]);
        }
    }
}

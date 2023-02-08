<?php

declare(strict_types=1);

namespace Sync\Handlers;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AddWebHook;
use Sync\Kommo\AuthService;
use Sync\Kommo\GetContactsAmo;
use Sync\Models\Account;

class AuthHandler extends AuthService implements RequestHandlerInterface
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
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['name'];
        if (//!isset(json_decode(file_get_contents('./tokens.json'), true)[$a]) //or
        !(Account::where('account_name', $a)->exists())) {
            $name = (new AuthService())->auth();
            $accessToken = $this->readToken($name);
            $this->apiClient->setAccessToken($accessToken)
                ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
            (new AddWebHook())->AddWebHook($this->apiClient);
            return new JsonResponse([
                ['name' => $name]
            ]);
        }
        $accessToken = $this->readToken($a);
        $this->apiClient->setAccessToken($accessToken)
            ->setAccountBaseDomain($accessToken->getValues()['base_domain']);
        (new AddWebHook())->AddWebHook($this->apiClient);
        return new JsonResponse([
            ['name' => $a]
        ]);
    }
}

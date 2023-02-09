<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;

use function PHPUnit\Framework\isJson;

class ImportAmoToDbHandler implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('No name!');
        }
        $accountId = (new \Sync\Kommo\GetContactsAmo())->GetId($name)['id'];
        $token = (new \Sync\Kommo\GetContactsAmo())->checkAuthToken($name);
        (new AccountController())->accountCreate([
            'account_name' => $name,
            'account_id' => $accountId,
            'token' => json_encode($token, JSON_PRETTY_PRINT),
        ]);
        return new JsonResponse(
            'успешная синхронизация'
        );
    }
}
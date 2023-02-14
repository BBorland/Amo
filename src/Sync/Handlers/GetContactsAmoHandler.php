<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AuthService;
use Sync\Kommo\GetContactsAmo;

class GetContactsAmoHandler extends AuthService implements RequestHandlerInterface
{

    /**
     * Получение контактов из amo
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('No name!');
        }
        $getContactsAmo = new GetContactsAmo();
        $bigArrayOfContacts = $getContactsAmo->getCont($name);
        $goodReturn = $getContactsAmo->makeArray($bigArrayOfContacts);
        return new JsonResponse(
            $goodReturn
        );
    }
}

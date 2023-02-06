<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;

class ImportAmoToDbHandler implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('No name!');
        }
        $getContactsAmo = (new \Sync\Kommo\GetContactsAmo());
        $bigArrayOfContacts = $getContactsAmo->GetCont($name);
        $goodReturn = $getContactsAmo->makeArray($bigArrayOfContacts);
        (new AccountController())->accountCreate($goodReturn);
        return new JsonResponse(
            $goodReturn
        );
    }
}
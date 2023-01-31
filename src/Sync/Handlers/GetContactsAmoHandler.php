<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AuthService;

class GetContactsAmoHandler extends AuthService implements RequestHandlerInterface
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
        if (!isset(json_decode(file_get_contents('./tokens.json'), true)[$name])) {
            (new AuthService())->auth();
        }
        $getContactsAmo = (new \Sync\Kommo\GetContactsAmo);
        $bigArrayOfContacts = $getContactsAmo->GetCont($name);
        $goodReturn = $getContactsAmo->makeArray($bigArrayOfContacts);
        return new JsonResponse(
            $goodReturn
        );
    }
}

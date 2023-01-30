<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Sync\Kommo\GetContactsUni;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetContactsUniHandler extends GetContactsUni implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $a = $request->getQueryParams()['email'];
        return new JsonResponse(
            (new GetContactsUni)->getContactsUni($a)
        );
    }
}
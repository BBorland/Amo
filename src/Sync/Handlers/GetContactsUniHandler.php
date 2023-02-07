<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Sync\Kommo\GetContactsUni;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetContactsUniHandler extends GetContactsUni implements RequestHandlerInterface
{

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $email = $request->getQueryParams()['email'];
        return new JsonResponse(
            (new GetContactsUni())->getContactsUni($email)
        );
    }
}
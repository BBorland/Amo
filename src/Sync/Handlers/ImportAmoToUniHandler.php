<?php

declare(strict_types=1);

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\ImportAmoToUni;

class ImportAmoToUniHandler extends ImportAmoToUni implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('Нету имени');
        }
        $getContactsAmo = (new \Sync\Kommo\GetContactsAmo);
        $bigArrayOfContacts = $getContactsAmo->GetCont($name);
        $goodReturn = $getContactsAmo->makeArray($bigArrayOfContacts);
        return new JsonResponse(
            (new ImportAmoToUni())->ImportAmoToUni($goodReturn)
        );
    }
}
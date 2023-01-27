<?php

namespace Sync\Handlers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\AuthService;

class GetContactsHandler extends AuthService implements RequestHandlerInterface
{

    public function filterArray($value): bool
    {
        return ($value == 'Email');
    }


    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('No name!');
        }
        $bigArrayOfContacts = (new \Sync\Kommo\GetContacts)->GetCont($name);
        $goodReturn = [];
        $emails = [];
        foreach ($bigArrayOfContacts as $key => $value) {
            if (isset($value['custom_fields_values'])) {
                foreach ($value['custom_fields_values'] as $key3 => $field3) {
                    if ($field3['field_code'] == 'EMAIL') {
                        $ifEmail = true;
                        foreach ($field3['values'] as $key4 => $field4) {
                            $emails[] = $field4['value'];
                        }
                    }
                }
                $goodReturn[] = ['name' => $value['name'], 'emails' => $emails];
                $emails = [];
            } else {
                $goodReturn[] = ['name' => $value['name'], 'emails' => null];
            }
        }
        return new JsonResponse(
            $goodReturn
        );
    }
}

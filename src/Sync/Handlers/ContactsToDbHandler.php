<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;
use Sync\Core\Controllers\ContactController;
use Sync\Kommo\GetContactsAmo;

class ContactsToDbHandler implements RequestHandlerInterface
{
    /**
     * Отправляет данные из amo в бд
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getQueryParams()['name'];
        if (!isset($name)) {
            exit('No name!');
        }
        $bigArrayOfContacts = (new GetContactsAmo())->GetCont($name);
        foreach ($bigArrayOfContacts as $contact) {
            $contactName = $contact['name'];
            $contactId = $contact['id'];
            $accountId = $contact['account_id'];
            foreach ($contact['custom_fields_values'] as $field) {
                if ($field['field_code'] == 'EMAIL') {
                    foreach ($field['values'] as $value) {
                        if ($value['enum_code'] == 'WORK') {
                            (new ContactController())->contactCreate([
                                'contact_name' => $contactName,
                                'email' => $value['value'],
                                'account_id' => $accountId,
                                'contact_id' => $contactId,
                            ]);
                            (new AccountController())->enumInsert([
                                'account_name' => $name,
                                'enum' => $value['enum_id']
                            ]);
                        }
                    }
                }
            }
        }
        return new JsonResponse(
            $name
        );
    }
}

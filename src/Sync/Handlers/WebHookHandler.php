<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Core\Controllers\AccountController;
use Sync\Kommo\ArraySortToUni;
use Sync\Kommo\ImportAmoToUni;
use Sync\Models\Contact;

class WebHookHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $accountId = $data['account']['id'];
        $enum = (new AccountController())->accountGetEnum($accountId);
        file_put_contents('./test.txt', json_encode($data, JSON_PRETTY_PRINT));
        switch (isset($data['contacts']['update'])) {
            case 1:
                foreach ($data['contacts']['update'] as $update) {
                    $contactId = $update['id'];
                    $arrayToSend = (new ArraySortToUni())->arraySortToUni(1, $update, $contactId, $enum);
                    (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
                    $arrayToSend = (new ArraySortToUni())->arraySortToUni(0, $update, $contactId, $enum);
                    (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
                    Contact::where('contact_id', $contactId)->delete();
                    foreach ($arrayToSend as $arrayToUpdate) {
                        file_put_contents('./test.txt', json_encode($arrayToUpdate));
                        Contact::create([
                            'contact_id' => $contactId,
                            'contact_name' => $arrayToUpdate[1],
                            'account_id' => $accountId,
                            'email' => $arrayToUpdate[0],
                        ]);
                    }
                }
                break;
            case 0:
                if (isset($data['contacts']['add'])) {
                    foreach ($data['contacts']['add'] as $add) {
                        $contactId = $add['id'];
                        $arrayToSend = (new ArraySortToUni())->arraySortToUni(0, $add, $accountId, $enum);
                        if (!empty($arrayToSend)) {
                            (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
                            foreach ($arrayToSend as $arrayToAdd) {
                                Contact::updateOrCreate([
                                    'contact_id' => $contactId,
                                    'email' => $arrayToAdd[0],
                                ], [
                                    'contact_name' => $arrayToAdd[1],
                                    'account_id' => $accountId,
                                ]);
                            }
                        } else {
                            exit();
                        }
                    }
                }
                break;
        }
        return new JsonResponse(
            $data
        );
    }
}
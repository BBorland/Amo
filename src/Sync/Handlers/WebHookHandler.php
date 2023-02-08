<?php

namespace Sync\Handlers;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sync\Kommo\ImportAmoToUni;

class WebHookHandler implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        if (isset($data['contacts']['update'])) { // TODO: проще и лучше использовать switch-case
            foreach ($data['contacts']['update'] as $update) {
                $name = $update['name']; // TODO: DRY
                foreach ($update['custom_fields'] as $custom_field) {
                    if ($custom_field['code'] == 'EMAIL') {
                        foreach ($custom_field['values'] as $value) {
                            $email = $value['value'];
                            $arrayToSend[] = [$email, $name];
                        }
                    }
                }
                if (isset($arrayToSend)) {
                    (new ImportAmoToUni())->ImportAmoToUni($arrayToSend); // TODO: старые почты из унисендера не удаляться
                } else {
                    exit();
                }
            }
        }
        if (isset($data['contacts']['add'])) {
            foreach ($data['contacts']['add'] as $add) {
                $name = $add['name']; // TODO: DRY
                foreach ($add['custom_fields'] as $custom_field) {
                    if ($custom_field['code'] == 'EMAIL') {
                        foreach ($custom_field['values'] as $value) {
                            $email = $value['value'];
                            $arrayToSend[] = [$email, $name];
                        }
                    }
                }
                if (isset($arrayToSend)) { // TODO: DRY + заменить isset на empty(...), иначе else никогда не выполнится
                    (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
                } else {
                    exit();
                }
            }
        }
        return new JsonResponse(
            $data
        );
    }
}
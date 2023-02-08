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
        if (isset($data['contacts']['update'])) {
            foreach ($data['contacts']['update'] as $update) {
                $name = $update['name'];
                foreach ($update['custom_fields'] as $custom_field) {
                    if ($custom_field['code'] == 'EMAIL') {
                        foreach ($custom_field['values'] as $value) {
                            $email = $value['value'];
                            $arrayToSend[] = [$email, $name];
                        }
                    }
                }
                (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
            }
        }
        if (isset($data['contacts']['add'])) {
            foreach ($data['contacts']['add'] as $add) {
                $name = $add['name'];
                foreach ($add['custom_fields'] as $custom_field) {
                    if ($custom_field['code'] == 'EMAIL') {
                        foreach ($custom_field['values'] as $value) {
                            $email = $value['value'];
                            $arrayToSend[] = [$email, $name];
                        }
                    }
                }
                (new ImportAmoToUni())->ImportAmoToUni($arrayToSend);
            }
        }
        return new JsonResponse(
            $data
        );
    }
}
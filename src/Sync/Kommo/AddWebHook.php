<?php

namespace Sync\Kommo;

use AmoCRM\OAuth2\Client\Provider\AmoCRMException;
use Laminas\Diactoros\Response\JsonResponse;

class AddWebHook
{
    /**
     * Подключает Webhook
     * @param $apiClient
     * @return JsonResponse
     */
    public function AddWebHook($apiClient): JsonResponse
    {
        $webHookModel = (new \AmoCRM\Models\WebhookModel())
            ->setSettings([
                'add_contact',
                'update_contact',
            ])
            ->setDestination($_ENV['webhookRedirectionUri']);
        try {
            $response = $apiClient
                ->webhooks()
                ->subscribe($webHookModel)
                ->toArray();
        } catch (AmoCRMException $e) {
            exit($e->getMessage());
        }
        return new JsonResponse($response);
    }
}

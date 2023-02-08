<?php

namespace Sync\Kommo;

use Laminas\Diactoros\Response\JsonResponse;

class AddWebHook
{
    /**
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

        $response = $apiClient
            ->webhooks()
            ->subscribe($webHookModel)
            ->toArray();

        return new JsonResponse($response);
    }
}
<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class GetContactsUni
{
    /**
     * @var UnisenderApi
     */
    private UnisenderApi $uni;

    public function __construct()
    {
        $this->uni = new UnisenderApi($_ENV['unisender_key']);
    }

    /**
     * Getting contact from Unisender
     *
     * @param string $email
     * @return string
     */
    public function getContactsUni(string $email): string
    {
        if ($this->uni->isContactInLists([$email])) {
            return $this->uni->getContact(["email" => $email, "format" => "json"]);
        }
    }
} // TODO: PSR
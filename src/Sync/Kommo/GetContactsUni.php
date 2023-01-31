<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class GetContactsUni
{
    /**
     * @var string
     */
        private string $apikey = "6qea616r3cfkkkhy8ko3beorj4pwzi69ag1ke53a";

    /**
     * @var UnisenderApi
     */
    private UnisenderApi $uni;

    public function __construct()
    {
        $this->uni = new UnisenderApi($this->apikey, 'UTF-8', 4, null, false);
    }

    /**
     * Getting contact from Unisender
     *
     * @param string $email
     * @return false|string|void
     */
    public function getContactsUni(string $email)
    {
        if ($this->uni->isContactInLists([$email])) {
            return $this->uni->getContact(["email" => $email, "format" => "json"]);
        }
    }
}
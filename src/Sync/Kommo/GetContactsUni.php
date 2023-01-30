<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class GetContactsUni
{
    private string $apikey = "6qea616r3cfkkkhy8ko3beorj4pwzi69ag1ke53a";

    private UnisenderApi $uni;

    public function __construct()
    {
        $this->uni = new UnisenderApi($this->apikey, 'UTF-8', 4, null, false);
    }

    public function getContactsUni($email)
    {
        if ($this->uni->isContactInLists([$email])) {
            return $this->uni->getContact(["email" => $email]);
        }
    }
}
<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class ImportAmoToUni
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
     * @param array $array
     * @return string
     */
    public function ImportAmoToUni($array): string
    {
        return $this->uni->importContacts(['field_names' => ['email', 'Name'], 'data' => $array]);
    }
}
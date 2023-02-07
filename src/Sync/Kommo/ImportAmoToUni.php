<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class ImportAmoToUni
{
    /**
     * @var string
     */
    private string $apikey = "6qea616r3cfkkkhy8ko3beorj4pwzi69ag1ke53a"; // TODO: DRY

    /**
     * @var UnisenderApi
     */
    private UnisenderApi $uni;

    public function __construct() // TODO: PHPDocs
    {
        $this->uni = new UnisenderApi($this->apikey, 'UTF-8', 4, null, false); // TODO: DRY
    }

    /**
     * @param array $array
     * @return string
     */
    public function ImportAmoToUni(array $array): string
    {
        return $this->uni->importContacts(['field_names' => ['email', 'Name'], 'data' => $array]);
    }
}
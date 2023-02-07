<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class ImportAmoToUni
{
    /**
     * @var UnisenderApi
     */
    private UnisenderApi $uni;

    public function __construct() // TODO: PHPDocs
    {
        $this->uni = new UnisenderApi($this->$_ENV['unisender_key']);
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
<?php

namespace Sync\Kommo;

use Unisender\ApiWrapper\UnisenderApi;

class ImportAmoToUni
{
    /**
     * @var UnisenderApi
     */
    private UnisenderApi $uni;

    /**
     *
     */
    public function __construct()
    {
        $this->uni = new UnisenderApi($_ENV['unisender_key']);
    }

    /**
     * Отправляет контакты в Unisender
     * @param array $array
     * @return string
     */
    public function ImportAmoToUni(array $array): string
    {
        return $this->uni->importContacts(['field_names' => ['email', 'Name', 'delete'], 'data' => $array]);
    }
}

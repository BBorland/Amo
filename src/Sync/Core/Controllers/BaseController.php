<?php

namespace Sync\Core\Controllers;

use Sync\Core\DBConnection;

abstract class BaseController
{
    /**
     *
     */
    public function __construct()
    {
        (new DBConnection())->getCapsule();
    }
}

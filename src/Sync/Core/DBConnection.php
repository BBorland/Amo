<?php

namespace Sync\Core;

use Illuminate\Database\Capsule\Manager as Capsule;
use InvalidArgumentException;
use PDOException;

class DBConnection
{
    /** @var Capsule */
    private Capsule $capsule;

    public function __construct()
    {
        $config = (include './config/autoload/orm_config.global.php')['database'];
        $this->capsule = new Capsule();
        $this->capsule->addConnection($config);
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    /**
     * @return Capsule
     * @throws PDOException
     * @throws InvalidArgumentException
     */
    public function getCapsule(): Capsule// TODO: PHPDocs
    {
        return $this->capsule;
    }
}

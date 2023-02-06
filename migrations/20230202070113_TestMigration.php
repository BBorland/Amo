<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Capsule\Manager as Capsule;

class TestMigration extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('app_db', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->drop('app_db');
    }
}

<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Capsule\Manager as Capsule;

class SetAccounts extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('emails');
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('accounts');
    }
}

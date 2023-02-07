<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('account_name');
            $table->string('unisender_key')->nullable();
            $table->json('token')->nullable();
            $table->string('account_id')->nullable();
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

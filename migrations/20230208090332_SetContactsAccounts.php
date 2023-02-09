<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;
class SetContactsAccounts extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('account_contact', function (Blueprint $table) {
            $table->integer('account_id');
            $table->integer('contact_id');
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('account_contact');
    }
}

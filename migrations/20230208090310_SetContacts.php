<?php

use Phpmig\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;
class SetContacts extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        Capsule::schema()->create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name');
            $table->string('email')->nullable();
            $table->string('account_id')->nullable();
            $table->string('contact_id');
            $table->timestamps();
        });
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        Capsule::schema()->dropIfExists('contacts');
    }
}

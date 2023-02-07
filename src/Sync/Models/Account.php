<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * @var string[]
     */
 //   protected $fillable = ['account_name', 'unisender_key', 'token', 'enum_code'];
    /**
     * @var string
     */
    protected $table = 'accounts'; // TODO: можно не указывать, если таблица одноименна модели, но во множественном числе

    protected $guarded = []; // TODO: на 12 строке правильный вариант, он безопаснее
}
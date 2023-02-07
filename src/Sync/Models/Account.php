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
    protected $table = 'accounts';

    protected $guarded = [];
}
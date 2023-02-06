<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['Name', 'emails'];
    /**
     * @var string
     */
    protected $table = 'accounts';
}
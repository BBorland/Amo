<?php

namespace Sync\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['contact_name', 'email', 'account_id', 'contact_id'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockedEmail extends Model
{
    protected $table = 'blockedemail';
    public $timestamps = false;
}

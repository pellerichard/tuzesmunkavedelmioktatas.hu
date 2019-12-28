<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    protected $table = 'emailnotification';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangePassword extends Model
{
    protected $table = 'changepassword';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';
}

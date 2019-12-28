<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'group';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';
}

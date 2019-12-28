<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'answer';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';
}

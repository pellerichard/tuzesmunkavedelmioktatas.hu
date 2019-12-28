<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Test;

class TestInput extends Model
{
    protected $table = 'testinput';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';

    public function test() {
        $this->belongsTo(Test::class, 'id', 'testId');
    }
}

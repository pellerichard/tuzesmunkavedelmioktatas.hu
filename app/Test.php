<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TestInput;

class Test extends Model
{
    protected $table = 'test';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';

    public function inputs() {
        return $this->hasMany(TestInput::class, 'testId', 'id');
    }
}

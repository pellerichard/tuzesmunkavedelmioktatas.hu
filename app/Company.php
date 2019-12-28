<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $casts = [
        'project' => 'integer',
    ];

    protected $table = 'company';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';

    public function con()
    {
        return $this->hasMany('App\conAccountCompany', 'companyId', 'id');
    }

    public static function countDiaries($id) {
        $diaries = DiaryLog::where('companyId', $id)->count();
        return $diaries;
    }

    public static function latestDiaryDate($id) {
        $diaries = DiaryLog::where('companyId', $id)->orderBy('id', 'desc')->first();
        return isset($diaries->creDate) ? $diaries->creDate : 'Soha';
    }

    public function findCompanyByRef($value) {
        return count($this->where('ref','=',$value)->pluck('id'));
    }

    public function project() {
        return $this->project;
    }

    public function user() {
        return $this->id;
    }

    public function id() {
        return $this->id;
    }
}

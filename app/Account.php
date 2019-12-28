<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ModuleConst;

class Account extends Model
{
    protected $table = 'account';
    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';

    public function users() {
        return $this->belongsToMany('App\conAccountCompany', 'accountId', 'id');
    }

    public function con() {
        return $this->hasMany('App\conAccountCompany', 'accountId', 'id');
    }

    public function answers() {
        return $this->hasMany('App\Answer', 'creUserId', 'id');
    }

    public function fullName() {
        return "{$this->firstName} {$this->lastName}";
    }

    public static function getGroupNames($userId) {
        $account = Account::query()->where('id', $userId)->first();
        $groupIds = is_array($account->groups) ? $account->groups : unserialize($account->groups);
        if(empty($groupIds)) {
            return 'Nincs';
        }
        $groups = Group::query()->whereIn('id', $groupIds)->get()->all();
        if(!count($groups)) {
            return 'Nincs';
        }
        $groupNames = [];
        foreach($groups as $group) {
            $groupNames[] = $group->name;
        }
        return implode(', ',$groupNames);
    }

    public function getEducationReason() {
        return ModuleConst::getEducationReason($this->educationReason);
    }
}

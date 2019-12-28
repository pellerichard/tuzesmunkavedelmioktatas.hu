<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class conAccountCompany extends Model
{
    const STATE_ACTIVE = 1;
    const STATE_REMOVED = 3;

    protected $userGroups = [];
    protected $table = 'conaccountcompany';

    const CREATED_AT = 'creDate';
    const UPDATED_AT = 'modDate';

    public function users()
    {
        return $this->hasOne('App\Account', 'id', 'accountId');
    }

    public function user()
    {
        return $this->hasOne('App\Account', 'id', 'accountId');
    }


    public function getUserPermissionName() {
        return ModuleConst::PermissionNames[$this->permission];
    }

    public function getUserGroups() {
        $groupIds = is_array($this->relations['users']->groups) ? $this->relations['users']->groups : unserialize($this->relations['users']->groups);
        if(empty($groupIds)) {
            return [];
        }
        $groups = Group::query()->select('name')->whereIn('id', $groupIds)->get()->all();
        foreach($groups as $group) {
            $this->userGroups[] = $group->name;
        }
        return $this->attributes['userGroups'] = $this->userGroups;
    }
}
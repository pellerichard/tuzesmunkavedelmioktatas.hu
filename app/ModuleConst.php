<?php

namespace App;

class ModuleConst {
    const PERMISSION_NORMAL = 1;
    const PERMISSION_COMPANY_OWNER = 2;
    const PERMISSION_ADMIN = 3;

    const STATE_ACTIVE = 1;
    const STATE_REMOVED = 3;

    const PermissionNames = [
        self::PERMISSION_NORMAL => 'Munkatárs',
        self::PERMISSION_COMPANY_OWNER => 'Cégvezető',
        self::PERMISSION_ADMIN => 'Admin'
    ];

    const EDUCATION_NONE = 0;
    const EDUCATION_START_WORK = 1;
    const EDUCATION_CONTINOUS_EDUCATION = 2;
    const EDUCATION_FIRE_POSITION_CHANGE = 3;
    const EDUCATION_NEW_TECHNOLOGY = 4;
    const EDUCATION_UNIQUE_ITEM_USAGE = 5;
    const EDUCATION_RISK_REQUIREMENTS = 6;
    const EDUCATION_CHANGE_OF_WORK_ROLE = 7;

    const EducatonReason = [
        self::EDUCATION_NONE => "Nincs meghatározva",
        self::EDUCATION_START_WORK => "Munkába állás",
        self::EDUCATION_CONTINOUS_EDUCATION => "Ismétlődő oktatás",
        self::EDUCATION_FIRE_POSITION_CHANGE => "Tűzvédelmi helyzet megváltozása",
        self::EDUCATION_NEW_TECHNOLOGY => "Új technológia bevezetése",
        self::EDUCATION_UNIQUE_ITEM_USAGE => "Egyéni védőeszközök használata",
        self::EDUCATION_RISK_REQUIREMENTS => "Kockázatértékelésből adódó követelmények",
        self::EDUCATION_CHANGE_OF_WORK_ROLE => "Munkakör megváltozása"
    ];

    public static function getEducationReason($id) {
        return self::EducatonReason[$id];
    }
}
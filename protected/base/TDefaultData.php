<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\base;

use app\models\User;
use app\modules\blog\models\Category as BlogCategory;
use app\modules\blog\models\Post as Blog;
use app\modules\feature\models\Feature;
use app\modules\feature\models\Type as FeatureType;

class TDefaultData
{

    public static function data()
    {
        User::log(__FUNCTION__ . ' =>Default data start');
        User::addData([
            [
                'full_name' => 'Admin',
                'email' => 'admin@toxsl.in',
                'role_id' => User::ROLE_ADMIN,
                'password' => 'admin@123'
            ]
        ]);      

        User::log(__FUNCTION__ . " ==> End");
    }
}


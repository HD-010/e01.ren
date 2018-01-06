<?php
namespace app\modules\openapi\models;

use yii;

use app\models\User;

/**
 * @author 弘德誉曦
 * 用户资料管理模块
 * 在这个模块中，管理着基本用户资料、企业用户资料、个人用户资料的查询及资料整合
 *
 */
class UserProfiles 
{
    
    public function singUp(){
        global $uname,$pswd1;
        
        $model = new User();
        $model->PSWD = $uname;
        $model->UNAME = $pswd1;
        $model->TYPE = '';
        $res = $model->save();
        return $res;
    }
}


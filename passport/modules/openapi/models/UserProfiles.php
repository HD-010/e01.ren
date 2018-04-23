<?php
namespace app\modules\openapi\models;

use app\models\User;
use app\models\Validata;

/**
 * @author 弘德誉曦
 * 用户资料管理模块
 * 在这个模块中，管理着基本用户资料、企业用户资料、个人用户资料的查询及资料整合
 *
 */
class UserProfiles
{
    public function singUp($data){
        global $uname,$pswd;
        
        $countType = strtoupper($data['countType']);
        $model = new User();
        $model->$countType = $uname;
        $model->PSWD = $data['pswd'];
        $model->TYPE = '';
        $model->TOKEN = $data['token'];
        $res = $model->save();
        return $res;
    }
    
    /**
     * 判断用户登录的账号类型，反回是email或mobile或false
     */
    public function countType()
    {
        global $uname;
        
        $Validate = new Validata();
        $role = ['email','mobile'];
        foreach($role as $k => $v){
            if($Validate->$v($uname)){
                return $v;
            }
        }
        return false;
    }
    
    
    /**
     * 安全字符处理
     * 
     */
    public function securityStr($str){
        $st =str_split(md5($str),1);
        $childst1 = $childst2 = '';
        for($i = 0;$i < count($st); $i ++ ){
            if($i%3){
                $childst1 .= $st[$i];
            }
            if($i%2){
                $childst2 .= $st[$i];
            }
        }
        $str = substr($childst1.$childst2,1,29);
        return md5($str);
    }
}



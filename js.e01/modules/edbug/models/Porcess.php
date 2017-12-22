<?php
namespace app\modules\edbug\models;

class Porcess
{
    //处理传入的参数
    public static function request(){
        //从$_REQUSEA中获取参数的名称
        $keys = array_keys($_REQUEST);
        extract($_REQUEST);
        for($i = 0; $i < count($keys); $i ++){
            $keyName = htmlspecialchars($keys[$i]);
            $GLOBALS[$keys[$i]] = $$keyName;
        }
    }
}


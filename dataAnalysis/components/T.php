<?php
namespace app\components;

class T
{
    public static function arrayValue($key,$array,$default=null){
        if(array_key_exists($key,$array)){
            return $array[$key];
        }else{
            return $default;
        }
    }
}


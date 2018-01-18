<?php
namespace app\components;

class T
{
    
    /**
     * 获取ip地址
     * @return string|unknown
     */
    public static function IP(){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if(!empty($_SERVER['REMOTE_ADDR']))
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }else{
            $ip='0.0.0.0';
        }
        return $ip;
    }
    
    
    /**
     * 获取数组中的值
     * @param sting $key
     * @param array $array
     * @param mixed $default
     * @return unknown|string
     */
    public static function arrayValue($key,$array,$default=null){
        if(array_key_exists($key,$array)){
            return $array[$key];
        }else{
            return $default;
        }
    }
}


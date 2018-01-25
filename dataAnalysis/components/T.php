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
    
    /**
     * 为数据添加status状态标识
     * @param unknown $data
     */
    public static function addStatus($data){
        if(empty($data)) return ['status' => 0];
        return array(
            'status' => 1,
            'data' => $data
        );
    }
    
    public static function outJson($data){
        //启用output buffering机制。 Output buffering支持多层次 — 例如，可以多次调用 ob_start() 函数。
        ob_start();
        
        //发送output buffer（输出缓冲）并禁用output buffering机制。
        ob_end_flush();
        
        //清除output buffer但不发送，并禁用output buffering。
        ob_end_clean();
        
        //将当前的output buffer返回成一个字符串。允许你处理脚本发出的任何输出。
        ob_get_contents();
        echo \GuzzleHttp\json_encode($data);
        
    }
}
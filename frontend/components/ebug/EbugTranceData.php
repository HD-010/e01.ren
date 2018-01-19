<?php
namespace app\components\ebug;

class EbugTranceData
{
    public static function curl_post($data){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,EBUG_SERVER);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //执行的最长秒数
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        //打印获得的数据
        return $output;
    }
}


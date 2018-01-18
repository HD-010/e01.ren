<?php
namespace app\components\sensors;

class EbugTranceData
{
    public static function curl_post($url,$data){
        $post_data = [
            "data" => json_encode($data),
        ];
        
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        
        //打印获得的数据
        return $output;
    }
}


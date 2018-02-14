<?php
namespace app\modules\analysis\models;

use \Yii;

class Parse
{
    /**
     * 获取视图小部件
     * 批量获取视图部件主要用于ajax一次请求多个视图小部件
     * 获取单个视图部件主要用于服务端视图中请求的单视图小部件
     * param $data string|array 一个获多个视图的名称
     * 
     * return string|json
     */
    public static function getWget($data){
        $contents = array();
        if(is_array($data)){
            //生成多个视图的数组
            foreach($data as $k => $v){
                $filePath = Yii::getAlias('@app/components/views/wget/'.$v.'.htm');
                if(file_exists($filePath)){
                    $contents[$k] = file_get_contents($filePath);
                }
            }
            //返回多个视图的json对象
            return self::renderJson($contents);
        }else{
            //生成单个视图的字符串
            $filePath = Yii::getAlias('@app/components/views/wget/'.$data.'.htm');
            if(file_exists($filePath)){
                $contents = file_get_contents($filePath);
            }
            //返回单个视图的字符串
            return $contents;
        }
    }
    
    //返回json
    public static function renderJson($data){
        echo \GuzzleHttp\json_encode($data);
    }
}
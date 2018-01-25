<?php
namespace app\modules\analysis\models;

use \Yii;

class Parse
{
    //获取视图小部件
    public static function getWget($data){
        $contents = file_get_contents(Yii::getAlias('@app/components/views/wget/'.$data.'.htm'));
        return $contents;
    }
    
    //返回json
    public static function renderJson($data){
        echo \GuzzleHttp\json_encode($data);
    }
}
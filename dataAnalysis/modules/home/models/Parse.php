<?php
namespace modules\home\models;

use Yii;

class Parse
{
    //获取视图小部件
    public function getWget($data){
        $contents = file_get_contents(Yii::getAlias('@app/components/views/wget/'.$data.'.htm'));
        echo $contents;
    }
}


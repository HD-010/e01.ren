<?php
namespace app\modules\home\models;

class DataProcess
{
    public $query;
    
    public function getData(){
        //验证数据库连接是否有效，如果无效则创建新的连接
        $conn = \Yii::$app->db;
        $sql = "select count(*) from e01ren_development_events";
        $command = $conn->createCommand($sql)->queryAll();
        return $command;
        //查询数据
    }
    
}


<?php
namespace app\modules\home\models;

use app\components\T;

class EventsDataProcess
{
    public $conn;
    public $query;
    
    //创建数据库连接
    public function conn(){
        if(!$this->conn){
            $this->conn = \Yii::$app->db;
        }
        return $this->conn;
    }
    
    
    public function getData(){
        //验证数据库连接是否有效，如果无效则创建新的连接
        $conn = \Yii::$app->db;
        $sql = "select distinct event,count(event),DATE_FORMAT(time,'%m-%d') as date from e01ren_development_events group by DATE_FORMAT(time,'%Y-%m-%d') ASC";
        $res = $conn->createCommand($sql)->queryAll();
        return $res;
    }
    
    /**
     * 获取事件分析选项中显示事件名称项内容
     * @return number[]|unknown[]
     */
    public function getAllEventNames(){
        $this->conn();
        $tableName = 'e01ren_development_events';
        $quer = "select distinct(event) as events from $tableName";
        $res = $this->conn->createCommand($quer)->queryAll();
        return T::addStatus($res);
    }
    
    /**
     * 获取事件分析选项中显示事件属性项内容
     * @return number[]|unknown[]
     */
    public function getAllEventAttr(){
        $this->conn();
        $tableName = 'e01ren_development_events';
        $quer = "select column_name,data_type from information_schema.columns where table_name = '$tableName' and (column_name<>'ID' AND column_name<>'EVENT')";
        $res = $this->conn->createCommand($quer)->queryAll();
        return T::addStatus($res);
    }
    
    /**
     * 获取事件分析选项内容
     * @return string
     */
    public function getEventOpt(){
        $data = array();
        //获取事件分析选项中显示事件名称项内容
        $data['names'] = $this->getAllEventNames();
        //获取事件分析选项中显示事件属性项内容
        $data['attrs'] = $this->getAllEventAttr();
        return $data;
    }
    
}
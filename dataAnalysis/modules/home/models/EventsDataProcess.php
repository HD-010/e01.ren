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
        $sql = "select distinct event,count(event) as total,DATE_FORMAT(time,'%m-%d') as date from e01ren_development_events group by DATE_FORMAT(time,'%Y-%m-%d') order by date ASC";
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
    
    /**
     * 封闭事件分析的数据
     * @param array $dataCount 查询数据库获得的原始数据
     * @return array 符合 echarts 使用的数据
     */
    public function getBlockOutForEcharts($dataCount){
        //数据中不重复事件名称(绘图时根据事件名称绘出相应的线)
        $uniqueEvent = array_unique(T::implodeArr('event',$dataCount));
        sort($uniqueEvent);
        
        //数据中所有事件发生的时间（日期）
        $nuiqueDate = T::implodeArr('date',$dataCount);
        
        //设置x轴坐标点
        $xAxis = [
            'type' => 'category',
            'boundaryGap' => false,
            'data' => $nuiqueDate,
        ];
        
        $data = array();
        //循环出所有事件
        for($i = 0; $i < count($uniqueEvent); $i++){
            //循环出所有日期
            $data[$i] = [
                'name' => '访问量',
                'type' => 'line',
                'smooth' => true,
                'data' => [],
            ];
             
            for($j = 0; $j < count($nuiqueDate); $j++){
                //到数组中查找相应日期对应的事件发生的次数，
                //如果没有相应的事件，则将其发生次数设置为0
                $keyI = $i . '.event';  //以事件名称为基准
                $keyD = $j . '.event';  //以日期为基准
                $keyV = $j . '.total';
                if(T::arrayValue($keyD, $dataCount) == T::arrayValue($keyI, $dataCount)){
                    $data[$i]['data'][$j] = T::arrayValue($keyV, $dataCount);
                }else{
                    $data[$i]['data'][$j] = 0;
                }
            }
        }
        
        return [
            'xAxis' => $xAxis,
            'data' => $data,
        ];
    }
    
    
}
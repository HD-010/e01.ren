<?php
namespace app\modules\home\controllers;

use Yii;
use app\components\T;
use yii\base\Controller;
use app\modules\analysis\models\Parse;
use app\modules\home\models\EventsDataProcess;

/**
 * @author 弘德誉曦
 * 数据统计控制器
 */
class EventsController extends Controller
{
    public $eventName;     //事件名称
    public $properties;    //事件属性
    
    /**
     * 数据分析-事件分析首页展示
     */
    public function actionView(){
        /**
         * 假设条件：
         * 1、统计所有数据
         */
        //获取所有数据的条数
        $dataCount = (new EventsDataProcess())->getData();
        
        /**************************************************/
        //处理筛选条件
        
        //根据筛选条件向数据库查找相应数据
        
        
        //将数据装入jgraph图表
        
        
        //输出显示数据
        //echo "<pre>".print_r($dataCount,1)."</pre>";
        return $this->render('event',[
            'data' => $dataCount
        ]);
    }
    
    
    /**
     * 获取数据分析-事件分析数据
     */
    public function actionData(){
        /**
         * 假设条件：
         * 1、统计各项事件发生的次数
         * 返回的数据格式如：
         * $data = [
		        [
		            'name' => '访问量',
		            'type' => 'line',
		            'smooth' => true,
		            'data' => [300, 280, 250, 260],
		        ]
	        ]
         */
        
        //获取所有数据的条数
        $dataCount = (new EventsDataProcess())->getData();
        /**************************************************/
        //处理筛选条件
        //格式化数据
        
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
        
        
        //echo "<pre>".print_r($data,1)."</pre>";exit;
        
        
        
        
        //根据筛选条件向数据库查找相应数据
        
        
        //将数据装入jgraph图表
        
        
        //输出显示数据
        echo T::outJson(['xAxis'=>$xAxis,'data'=>$data]);
        
    }
    
    
    
}
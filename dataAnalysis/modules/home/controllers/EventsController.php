<?php
namespace app\modules\home\controllers;

use Yii;
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
         * [
		        {
		            name:'用电量',
		            type:'line',
		            smooth: true,
		            data: [300, 280, 250, 260, 270, 300, 550, 500, 400, 390, 380, 390, 400, 500, 600, 750, 800, 700, 600, 400],
		            markArea: {
		                data: [ [{
		                    name: '早高峰',
		                    xAxis: '07:30'
		                }, {
		                    xAxis: '10:00'
		                }], [{
		                    name: '晚高峰',
		                    xAxis: '17:30'
		                }, {
		                    xAxis: '21:15'
		                }] ]
		            }
		        }
         */
        //获取所有数据的条数
        $dataCount = (new EventsDataProcess())->getData();
        
        
        /**************************************************/
        //处理筛选条件
        
        //根据筛选条件向数据库查找相应数据
        
        
        //将数据装入jgraph图表
        
        
        //输出显示数据
       
        echo json_encode($dataCount);
        
    }
    
    
    
}
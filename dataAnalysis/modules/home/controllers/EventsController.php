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
        
        /**************************************************/
        //处理筛选条件
        $where = [];
        $startDate  = Yii::$app->request->post('startData');
        $endDate  = Yii::$app->request->post('endData');
        $where[] = $startDate ? " and (time between $startDate and $endDate)" : "";
        
        $eventName  = Yii::$app->request->post('eventName');
        $where[] = $eventName ? " and event = $eventName " : "";
        
        $eventOpt = Yii::$app->request->post('eventOpt');
        /*
         * eventOpt为用参数：
        *countNumber总次数
        *userNumber触发用户数
        *personViews人均次数
        *IPNumberIP(去重)
        *conutries国家(去重)
        *provinces省份(去重)
        *cities城市(去重)
        */
        
        
        $where[] = $eventOpt ? " and " : "";
        //按事件的属性查询
        $eventAttr = Yii::$app->request->post('eventAttr');
        
        
        $addtionRelation = Yii::$app->request->post('addtionRelation');
        $AddtionAttr = Yii::$app->request->post('AddtionAttr');
        $AddtionOper = Yii::$app->request->post('AddtionOper');
        $AddtionValue = Yii::$app->request->post('AddtionValue');
        $post = Yii::$app->request->post();
        \EDebug::setInfo($post);
        
        //根据筛选条件向数据库查找相应数据
        
        
        //获取所有数据的条数
        $dataCount = (new EventsDataProcess())->getData();
        //格式化数据
        $dataForEcharts = (new EventsDataProcess())->getBlockOutForEcharts($dataCount);
        
        
        //echo "<pre>".print_r($data,1)."</pre>";exit;
        
        
        //输出显示数据
        echo T::outJson($dataForEcharts);
        
    }
    
    
    
}
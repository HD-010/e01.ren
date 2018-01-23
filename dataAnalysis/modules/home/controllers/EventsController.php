<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Parse;
use app\modules\home\models\DataProcess;

class EventsController extends Controller
{
    public $eventName;     //事件名称
    public $properties;    //事件属性
    
    //数据分析首页
    public function actionIndex(){
        /**
         * 假设条件：
         * 1、统计所有数据
         */
        //获取所有数据的条数
        $dataCount = (new DataProcess())->getData();
        
        
        /**************************************************/
        //处理筛选条件
        
        //根据筛选条件向数据库查找相应数据
        
        
        //将数据装入jgraph图表
        
        
        //输出显示数据
        
        return $this->render('index',[
            'data' => $dataCount
        ]);
    }
    
    
    public function actionGraph(){
        
    }
    
    
}
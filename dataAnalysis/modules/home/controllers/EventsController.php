<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;

class EventsController extends Controller
{
    public $eventName;     //事件名称
    public $properties;    //事件属性
    
    public function actionIndex(){
        //处理筛选条件
        
        
        //根据筛选条件向数据库查找相应数据
        
        
        //将数据装入jgraph图表
        
        
        //输出显示数据
        
        return $this->renderPartial('index',[]);
    }
}
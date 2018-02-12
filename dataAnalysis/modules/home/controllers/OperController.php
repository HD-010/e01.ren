<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;
use app\modules\home\models\EventsDataProcess;
use app\components\T;

/**
 * @author 弘德誉曦
 * 操作项数据控制器
 */
class OperController extends Controller
{
    
    /**
     * 获取事件分析选项中显示事件属性项内容
     */
    public function actionEventOpt(){
        //获取所有事件名称
        $data = (new EventsDataProcess())->getEventOpt();
        T::outJson($data);
    }
    
}
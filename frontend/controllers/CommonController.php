<?php
namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use app\components\EbugData;

class CommonController extends Controller
{
    /**
     * 将埋点过程中，在客户端获取的数据与服务端的数据合并，并写入神策日志
     * @param unknown $sensorsData
     * @param number $cache
     */
    public function actionElog(){
        //header('Content-Type: text/html; charset=utf-8'); //网页编码
        $saData =  Yii::$app->request -> post();
        $properties = json_decode($saData['saPre'],true);
        //获取数组形式的事件属性
        $res = EbugData::log([
            'properties' => $properties['properties'],     //事件属性
            'eventType' => $properties['eventType'],     //事件类型    如：PageView|UserAgent|WebClick
            'eventName' => $properties['eventName'],
        ],$saData['cache']);
    }
    
}


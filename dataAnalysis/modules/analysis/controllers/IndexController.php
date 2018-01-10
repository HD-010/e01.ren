<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;

class IndexController extends Controller
{
    //待写入的数据格式
    public $info = '{"type":"profile_set","properties":{"TIME":"2018-01-10 12:01:00","WECHAT":"56565","QQ":"233"},"time":-657091719000,"distinct_id":"160c43903f0f8-0698e47c2c301e-454c092b-2073600-160c43903f1418","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##450"},"project":"taoshouyou","event":"gameview"}';

    public function actionIndex(){
        $schema = new Schema();
        //schema->setTableDesc('user');
        
        //初始化数据
        $process = new Process();
        $data = $process->initAnalysis($this->info);
        
        //校验字段的有效性
        $valid = $process->vaildFeilds();
        var_dump($valid) ;
        //有新增字段
        if($valid == -1){
            Schema::addFeild2table($process->getMoreFeilds());
        }
        //字段类型不一致
        if(!$valid){
            return;
        }
        
        //字段有效性校验通过则往下执行
        echo "<pre>";
        print_r($data->getType());
        echo "</pre>";
    }
}


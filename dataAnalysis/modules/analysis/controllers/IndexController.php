<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;

class IndexController extends Controller
{
    //待写入的数据格式
    public $info = '{"type":"profile_set","properties":{"TedFDsE":"2018-01-10 12:01:00","WweweHAT":"56565","QQ":"233"},"time":-657091719000,"distinct_id":"160c43903f0f8-0698e47c2c301e-454c092b-2073600-160c43903f1418","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##450"},"project":"taoshouyou","event":"gameview"}';

    public function actionIndex(){
        $schema = new Schema();
        //schema->setTableDesc('user');
        
        //初始化数据
        $process = new Process();
        $data = $process->initAnalysis($this->info);
        
        //校验字段的有效性
        $valid = $process->vaildFeilds();
        var_dump($valid) ;
        //有新增字段,为保证新字段能添加成功，保留5次添加机会，如果5次都添加失败，则不再往下执行
        if($valid == -1){
            $addSuccess = false;
            for($i = 0; $i < 5 || !$addSuccess; $i ++){
                if(Schema::addFeild2table($process->getMoreFeilds())) {
                    $addSuccess = true;
                    break;
                }
            }
            //执行失败返回。
            if(!$addSuccess) return;
        }
        //字段类型不一致
        if(!$valid){
            return;
        }
        //写入新记录
        
    }
}


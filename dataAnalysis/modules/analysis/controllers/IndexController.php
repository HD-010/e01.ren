<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;

class IndexController extends Controller
{
    //待写入的数据格式
    public $info = '{"type":"track","properties":{"addtime":"2018-01-10 12:01:00","WECHAT":"56565"},"time":-657091719000,"distinct_id":"160c43903f0f8-0698e47c2c301e-454c092b-2073600-160c43903f1418","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##450"},"project":"taoshouyou","event":"gameview"}';

    public function actionIndex(){
        $schema = new Schema();
        
        //初始化数据
        $process = new Process();
        $data = $process->initAnalysis($this->info);
        
        //校验字段的有效性
        $valid = $process->vaildFeilds();
        //有新增字段,为保证新字段能添加成功，保留5次添加机会，如果5次都添加失败，则不再往下执行
        if($valid == -1){
            Schema::addFeilds($process->feilds2sql());
            //$addSuccess = false;
            //for($i = 0; $i < 5 || !$addSuccess; $i ++){
                //if(Schema::addFeilds($process->feilds2sql())) {
                    //$addSuccess = true;
                    //echo "成功加入新字段！";
                    //break;
                //}
            //}
            //执行失败返回。
            //if(!$addSuccess) return;
        }
        //字段类型不一致,将数据数据写入errors表
        if(!$valid){
            Schema::insertInValidDAta($process->inValidData2sql());
            echo "字段类型不一致，已经将错误信息写入error表";
            return;
        }
        //将有效数据写入数据表
        $res = Schema::insertValidDAta($process->validData2sql());
        //如果数据写入失败，则将错误信息写入errors表
        if(!$res){
            Schema::insertInValidDAta($process->insertError2sql());
            echo "数据写入失败，已经将错误信息写入error表";
        }
    }
}


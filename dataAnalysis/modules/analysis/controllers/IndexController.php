<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;

class IndexController extends Controller
{
    //待写入的数据格式
    public $info = '{"type":"track","properties":{"$lib":"php","$lib_version":"1.5.0","$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"63.0.3239.132","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","global_isguest":true,"global_source":"PC","global_ua":"Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/63.0.3239.132 Safari\/537.36","productType":"WEB","$url":"http:\/\/webpc.taoshouyou.com\/game\/shaoxianduitest-6057-0-0","$url_path":"\/com\/statistics\/salog","$screen_height":1920,"$screen_width":1080,"$is_first_day":true,"$latest_traffic_source_type":"\u76f4\u63a5\u6d41\u91cf","$title":"\u5c11\u5148\u961fTest\u4ea4\u6613\u5e73\u53f0-\u6dd8\u624b\u6e38\u4ea4\u6613\u5e73\u53f0","$is_first_time":false,"game_name":"\u5c11\u5148\u961fTest","sellmode":"\u5168\u90e8\u4ea4\u6613\u7c7b\u578b","goods_name":"\u5168\u90e8\u5546\u54c1\u7c7b\u578b","os":"\u5168\u90e8\u624b\u673a\u7cfb\u7edf","client_name":"\u5168\u90e8\u5ba2\u6237\u7aef\u7c7b\u578b"},"time":-657033444000,"distinct_id":"160c43903f0f8-0698e47c2c301e-454c092b-2073600-160c43903f1418","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##447"},"project":"taoshouyou","event":"gameview"}';

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


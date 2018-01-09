<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;

class IndexController extends Controller
{
    //待写入的数据格式
    public $info = '{"type":"track","properties":{"$lib":"php","$lib_version":"1.5.0","$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"63.0.3239.132","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","global_isguest":false,"global_source":"PC","global_ua":"Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/63.0.3239.132 Safari\/537.36","productType":"WEB","$url":"http:\/\/webpc.taoshouyou.com\/game\/guichuidengzhimuyeguishi-5550-0-0","$url_path":"\/com\/statistics\/salog","$screen_height":1920,"$screen_width":1080,"$is_first_day":false,"$latest_traffic_source_type":"\u76f4\u63a5\u6d41\u91cf","$title":"\u9b3c\u5439\u706f\u4e4b\u7267\u91ce\u8be1\u4e8b\u4ea4\u6613\u5e73\u53f0-\u6dd8\u624b\u6e38\u4ea4\u6613\u5e73\u53f0","$is_first_time":false,"game_name":"\u9b3c\u5439\u706f\u4e4b\u7267\u91ce\u8be1\u4e8b","sellmode":"\u5168\u90e8\u4ea4\u6613\u7c7b\u578b","goods_name":"\u5168\u90e8\u5546\u54c1\u7c7b\u578b","os":"\u5168\u90e8\u624b\u673a\u7cfb\u7edf","client_name":"\u5168\u90e8\u5ba2\u6237\u7aef\u7c7b\u578b"},"time":-638199310000,"distinct_id":"39019","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##450"},"project":"taoshouyou","event":"gameview"}';

    public function actionIndex(){
        //$schema = new Schema();
        //$schema->setTableDesc('user');
        $process = new Process();
        
        $data = $process->ParseJson2arr($this->info);
        echo "<pre>";print_r($data);echo "</pre>";
       
    }
}


<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;
use app\modules\analysis\models\Process;
use app\modules\analysis\models\Client;

class IndexController extends Controller
{
    public $send = false;
    public $process;
    public $info;
    #************************************#
    #*************待写入的数据格式*************#
    //以下为标准数据格式：
    //public $info = '{"type":"track","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"63.0.3239.132","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","global_isguest":false,"global_source":"PC","global_ua":"Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/537.36 (KHTML, like Gecko) Chrome\/63.0.3239.132 Safari\/537.36","productType":"WEB","$url":"http:\/\/webpc.taoshouyou.com\/game\/fangkainasanguo2-4439-0-0","$url_path":"\/com\/statistics\/salog","$screen_height":1920,"$screen_width":1080,"$is_first_day":true,"$latest_traffic_source_type":"\u76f4\u63a5\u6d41\u91cf","$title":"\u653e\u5f00\u90a3\u4e09\u56fd2\u4ea4\u6613\u5e73\u53f0-\u6dd8\u624b\u6e38\u4ea4\u6613\u5e73\u53f0","$is_first_time":false,"game_name":"\u653e\u5f00\u90a3\u4e09\u56fd2","sellmode":"\u5168\u90e8\u4ea4\u6613\u7c7b\u578b","goods_name":"\u5168\u90e8\u5546\u54c1\u7c7b\u578b","os":"\u5168\u90e8\u624b\u673a\u7cfb\u7edf","client_name":"\u5168\u90e8\u5ba2\u6237\u7aef\u7c7b\u578b"},"time":-652731043000,"distinct_id":"39019","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saLog##D:\\WWW\\web_pc\\components\\SensorsData.php##447"},"project":"taoshouyou","event":"gameview"}';
    //public $info = '{"type":"track_signup","properties":{},"time":-656482041000,"distinct_id":"39019","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saSingUp##D:\\WWW\\web_pc\\components\\SensorsData.php##467"},"project":"taoshouyou","event":"$SignUp","original_id":"160d8d6efd75de-0f9697ed0be58-454c092b-2073600-160d8d6efd88bb"}';
    //public $info = '{"type":"profile_set","properties":{"$name":"弘德誉曦","$signup_time":"2018-09-07 06:14:01"},"time":-656482040000,"distinct_id":"39019","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\SensorsData##saSetProfiles##D:\\WWW\\web_pc\\components\\SensorsData.php##478"},"project":"taoshouyou"}';
    
    //以下为正在测试中的数据，格式必须与标准数据格式中的某条一致
    //public $info = '{"type":"track","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}';
    /*public $arrInfo = [
        '{"type":"track","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}',
        '{"type":"track","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}',
        '{"type":"track_signup","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}',
        '{"type":"track_signup","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}',
        '{"type":"track","properties":{"$ip":"127.0.0.1","$os":"Windows","$os_version":"unknown","$browser":"Chrome","$browser_version":"55.0.2883.87","$utm_matching_type":"Cookie\u6570\u636e\u7cbe\u51c6\u5339\u914d","$url":"e01.ren\/","$url_path":"\/","name":0,"age":"69"},"time":147683675,"distinct_id":"e81cf53b350ee-ac2bd3f32febe4-f3b41ee1-910eff5-a48d5b0e84333d","lib":{"$lib":"php","$lib_version":"1.5.0","$lib_method":"code","$lib_detail":"app\\components\\EdbugData##saLog##D:\\WWW\\e01.ren\\frontend\\components\\EdbugData.php##400"},"project":"tsytest","event":"eventName"}',
    ];
    */
    #************************************#
    
    public function actionStorage(){
        //初始化数据
        $this->process = new Process();
        $this->process->initAnalysis($this->info);
        //校验字段的有效性
        $valid = $this->process->vaildFeilds();
        //如果$valid==-1,表示有新增字段
        if($valid == -1){
            //添加新增字段
            Schema::addFeilds($this->process->feilds2sql());
        }
        //如果$valid==false,表示字段类型不一致,将数据数据写入errors表
        if(!$valid){
            Schema::insertInValidDAta($this->process->inValidData2sql());
            return;
        }
        //生成子sql语句
        $this->process->validData2sql();
        //如果不需要立即存储数据，则此反回。
        if(!$this->send) return;
        //向数据库存储数据
        $res = Schema::{$this->process->Storage().'ValidData'}();
        //存储完毕初始化存储需要
        $this->send = false;
        //如果数据写入失败，则将错误信息写入errors表
        if(!$res){
            Schema::insertInValidDAta($this->process->insertError2sql());
        }
    }
    
    /**
     * 数据存储前的数据处理
     * 根据token验证用户身份，如果验证失败则退出
     * 将接收到的json串，解码为含多条json数据的数组
     * 依次调用存储方法存储数据
     * @return boolean|string
     */
    public function actionTest(){
        $client = new Client();
        //数据存储前身份验证,验证失败则退出不作任何处理
        if(!$client->realClient()) return;
        
        //接收post传来的json数据
        $data = json_decode(Yii::$app->request->post('data', []));
        //$data = $this->arrInfo;
        //如果接收到的数据为空则不作任何处理
        if(empty($data)) return false;
        
        //初始化数据处理程序
        $this->process = new Process();
        //计算接收到待处理数据的条数
        $number = count($data);
        for($i =0; $i < $number; $i ++){
            //只有一条数据需要立即存储
            //当前数据是最后一条，需要立即存储
            if(($number == 1) || ($i == $number-1)){
                $this->send = true;
            }else{
                //当前数据的事件类型与下一条数据的事件类型不同，需要立即存储。
                $currData = $this->process->formaterData($data[$i]);
                $nextData = $this->process->formaterData($data[$i + 1]);
                if($currData['type'] != $nextData['type']){
                    $this->send = true;
                }
            }
            //设置当前处理数据
            $this->info = $data[$i];
            //调用数据存储程序
            $this->actionStorage();
        }
        //返回运行成功状态
        return 'success';
    }
    
    
    
    public function actionToken(){
        $str = "e01.ren";
        $client = new Client();
        $token = $client->createToken($str);
        echo $token;
    }
}


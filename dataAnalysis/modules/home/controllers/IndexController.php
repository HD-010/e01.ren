<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;

class IndexController extends Controller
{
    public function actionIndex(){
        $isguest = 0;
        //如果用户已经登录，则进入系统首页
        //输出显示数据
        if(!$isguest){
            return $this->render('index',[
                'data' => '登录后展示的首页'
            ]);
        }
        
        //如果用户没有登录，则展示系统引导页
        //输出显示数据
        if($isguest){
            return $this->render('home',[
                'data' => 'eAnalysis引导（注册）页'
            ]);
        }
    }
}


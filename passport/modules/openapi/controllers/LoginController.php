<?php
namespace app\modules\openapi\controllers;

use Yii;
use yii\base\Controller;
use app\modules\openapi\models\Porcess;
use app\modules\openapi\models\Login;

class LoginController extends Controller
{
    public function actions(){
        Yii::setAlias("@js", "");
        Yii::setAlias("@webAssets", "");
        //处理传入的参数
        Porcess::request();
    }
    
    
    
    /**
     * 用户退出系统
     */
    public function actionSingoOut(){
        echo "success";
        //$this->render('out',[456]);
    }
    
    
    /**
     * 用户登录系统
     */
    public function actionSingIn(){
        global $uname,$pwsd;
        $login = new Login();
        $state = $login->singIn();
        print_r($state);
    }
    
    
    /**
     * 新用户注册 
     */
    public function actionSingUp(){
        global $uname;
        print_r($uname);
        //echo "注册成功，已经成功登录！;
    }
    
    
    /**
     * 客户端首页
     * @return string
     */
    public function actionClient(){
        global $clientUrl;
        setcookie('clientUrl',$clientUrl,time() * 1.05);
        
        return $this->render('client',[
            "app" => Yii::getAlias("@webAssets/js/app/"),
            "js" => Yii::getAlias("@webAssets/js/")
        ]);
    }
    
    
}

?>
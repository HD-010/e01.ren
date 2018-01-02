<?php
namespace app\modules\openapi\controllers;

use Yii;
use yii\base\Controller;
use app\modules\openapi\models\Porcess;


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
    public function actionSingout(){
        echo "success";
        //$this->render('out',[456]);
    }
    /**
     * 用户登录系统
     */
    public function actionSingin(){
        global $distinctId;
        
        return $this->renderPartial("singin",['df'=>"kddk"]);
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
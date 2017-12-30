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
    
    

    public function actionOut(){
        header("Access-Control-Allow-Origin:*");
        echo "退出成功";
        //$this->render('out',[456]);
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
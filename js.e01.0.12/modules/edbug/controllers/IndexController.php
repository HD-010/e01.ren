<?php
namespace app\modules\edbug\controllers;

use Yii;
use yii\base\Controller;
use app\modules\edbug\models\Porcess;
use app\modules\edbug\models\Parse;


class IndexController extends Controller
{
    public function actions(){
        Yii::setAlias("@js", "@app/assets/js");
        Yii::setAlias("@webAssets", "@web/assets");
        //处理传入的参数
        Porcess::request();     
    }
    
    public function actionIndex(){
        global $oper;
        $main = ucfirst($oper ? $oper : "main"); 
        $parseMethod = new Parse();
        if(method_exists($parseMethod,'get'.$main)){
            $parseMethod->{'get'.$main}();
        }
    }
    
    
    /**
     * 客户端首页
     * @return string
     */
    public function actionClient(){
        global $clientUrl;
        setcookie('clientUrl',$clientUrl,time() * 1.05);
        return $this->render('client',['message' => "okkljhlk"]);
    }
}

?>
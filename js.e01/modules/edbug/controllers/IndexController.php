<?php
namespace app\modules\edbug\controllers;

use Yii;
use yii\base\Controller;
use app\modules\edbug\models\Porcess;
use app\modules\edbug\models\Parse;


class IndexController extends Controller
{
    public function actionIndex(){
        global $oper;
        //处理传入的参数
        Porcess::request();     
        $main = ucfirst($oper); 
        $parseMethod = new Parse();
        if(method_exists($parseMethod,'get'.$main)){
            $parseMethod->{'get'.$main}();
        }
    }
}

?>
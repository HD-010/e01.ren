<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Parse;

class WgetController extends Controller
{
    
    //数据分析首页
    public function actionGet(){
        echo Parse::getWget(Yii::$app->request->post('wgetName'));
    }
    
    
    
}
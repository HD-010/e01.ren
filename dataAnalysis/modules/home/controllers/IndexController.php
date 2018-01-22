<?php
namespace app\modules\home\controllers;

use Yii;
use yii\base\Controller;
exit("ok");
class IndexController extends Controller
{
    public function actionIndex(){
        
        return $this->renderPartial('home',[]);
    }
}


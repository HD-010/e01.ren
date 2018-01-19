<?php
namespace app\modules\analysis\models;

use Yii;
use app\models\Volidation;

class Client extends Volidation
{

    //数据写入前的身份验证,返回boolen
    public function realClient(){
        $token = Yii::$app->request->post('token');
        $serverName = Yii::$app->request->post('serverName');
        $str = parent::createToken($serverName);
        return ($token == $token) ? true : false;
    }
}


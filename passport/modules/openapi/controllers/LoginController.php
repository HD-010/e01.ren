<?php
namespace app\modules\openapi\controllers;

use Yii;
use yii\base\Controller;
use app\modules\openapi\models\Porcess;
use app\models\User;
use app\modules\openapi\models\UserProfiles;

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
        
    }
    
    
    /**
     * 用户登录系统
     */
    public function actionSingIn(){
        global $uname,$pswd;
        
        
        $identity = User::findOne(['UNAME' => $uname]);
        if(!$identity){
            return json_encode([
                'state' => 'fail',
                'message' => '用户不存在'
            ]);
        }
        $password = $identity->PSWD;
        if($password == $pswd){
            Yii::$app->user->login($identity);
            
            return json_encode([
                'state' => 'success',
                'id' => $identity->ID,
                'uname' => $identity->UNAME,
                'tel' => $identity->TEL
            ]);
        }else{
            return json_encode([
                'state' => 'fail',
                'message' => '用户名或密码不正确'
            ]);
        }
        
    }
    
    
    /**
     * 新用户注册 
     */
    public function actionSingUp(){
        global $Verification;
        
        //验证用户验证码
        if(!$Verification){
            return json_encode(['state'=>'fail','message'=>'验证码错误']);
        }
        //写入注册用户资料
        $singUp = new UserProfiles();
        if($singUp->singUp()){
            return json_encode(['state'=>'success','message'=>'注册成功']);
        }else{
            return json_encode(['state'=>'fail','message'=>'注册失败']);
        }
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
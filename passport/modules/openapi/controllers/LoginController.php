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
     * 接口信息：
       [REQUEST_URI] => /?r=openapi/login/sing-in&uname=15985496246&pswd=123456
       [QUERY_STRING] => r=openapi/login/sing-in&uname=15985496246&pswd=123456
       [REQUEST_METHOD] => GET
       
                    登录成功返回：
            json_encode([
                'state' => 'success',
                'id' => $identity->ID,
                'uname' => $identity->UNAME,
                'mobil' => $identity->MOBILE
            ]);
                    登录失败返回：    
            json_encode([
                'state' => 'fail',
                'message' => '用户名或密码不正确',
            ]); 
     */
    public function actionSingIn(){
        global $uname,$pswd,$message;
        
        //检测用户登录账类型，是tel|qq|uname
        $userProfiles = new UserProfiles();
        $countType = $userProfiles->countType($uname);
        
        //如果账户类型不存在，则返回错
        if(!$countType) {
            return json_encode(['state' => 'fail','message' => '用户不存在']);
        }
        
        //获取用户认证信息
        $identity = User::findOne([strtoupper($countType) => $uname]);
        if(!$identity){
            return json_encode([
                'state' => 'fail',
                'message' => '用户不存在',
            ]);
        } 
        
        $password = $identity->PSWD;
        if(strlen($pswd) != 32){
            $pswd = $userProfiles->securityStr($pswd);
        }
        
        if($password == $pswd){
            Yii::$app->user->login($identity);
            return json_encode([
                'state' => 'success',
                'id' => $identity->ID,
                'uname' => $identity->UNAME,
                'mobil' => $identity->MOBILE,
                'token' => $identity->TOKEN
            ]);
        }else{
            return json_encode([
                'state' => 'fail',
                'message' => '用户名或密码不正确',
            ]);
        }
        
    }
    
    
    /**
     * 新用户注册 
     */
    public function actionSingUp(){
        global $uname,$pswd, $Verification;
        
        //验证用户验证码
        if(!$Verification){
            return json_encode(['state'=>'fail','message'=>'验证码错误']);
        }
        //写入注册用户资料
        //检测用户注册账号类型，是tel|qq|uname
        $userProfiles = new UserProfiles();
        $data = [
            'countType' => $userProfiles->countType($uname),
            'pswd' => $userProfiles->securityStr($pswd),
            'token' => $userProfiles->securityStr(time())
        ];
        
        
        if($userProfiles->singUp($data)){
            return json_encode([
                'state'=>'success',
                'message'=>'注册成功',
                'uname' => $uname,
                'pswd' => $pswd,
            ]);
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
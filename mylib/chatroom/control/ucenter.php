<?php
class Ucenter extends Control{
    public $mid;        //用户id
    
    
    //登录验证
    public function ac_login(){
        global $pws;
        $uc = $this->model('uc');
        $res = $uc -> get_userinfor();
        
        if($res['state']){
            if($res['data'][0]['pws'] === Tools::processpws($pws)) {
                $_SESSION['mid'] = $res['data'][0]['id'];
                return $res;
            }
        }
        return Tools::isdata([]);
    }
    
}
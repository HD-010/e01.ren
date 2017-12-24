<?php
class Verification{
    
    //初始化....
    static function inst(){
        return new Verification;
    }
    
    
    /**
     * 正则校验总函数
     * @param string $func 校验名称，也是被调用 的子函数     
     * @param unknown $val  被校验的值
     */
    public function ver_pre($func,$val){
        return $this->$func($val);
    }
    
    
    
    
    /**************************************************************************
     * 正则验证项目调用的子函数
     */
    //空值
    public function isNull($s){
        return ($s === '') ? true : false ;
    }
    
    //校验是否全由数字组成
    public function isDigit ($s)
    {
        $patrn='/^[0-9]{1,20}$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串
    public function isRegisterUserName ($s)
    {
        $patrn='/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){4,19}$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验用户姓名：只能输入1-30个以字母开头的字串
    public function isTrueName ($s)
    {
        $patrn='/^[a-zA-Z]{1,30}$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验密码：只能输入6-20个字母、数字、下划线
    public function isPasswd ($s)
    {
        $patrn='/^(\w){6,20}$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验普通电话、传真号码：可以“+”开头，除数字外，可含有“-”
    public function isTel ($s)
    {
        //$patrn='/^[+]{0,1}(\d){1,3}[ ]?([-]?(\d){1,12})+$/';
        $patrn='/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验手机号码：必须以数字开头，除数字外，可含有“-”
    public function isMobil ($s)
    {
        $patrn='/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验邮政编码
    public function isPostalCode ($s)
    {
        //$patrn='/^[a-zA-Z0-9]{3,12}$/';
        $patrn='/^[a-zA-Z0-9 ]{3,12}$/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验电子邮箱
    public function isEmail ($s)
    {
        $patrn='/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/';
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
    
    //校验搜索关键字
    public function isSearch ($s)
    {
        $patrn="/^[^`~!@#$%^&*()+=|\\\][\]\{\}:;'\,.<>/?]{1}[^`~!@$%^&()+=|\\\][\]\{\}:;'\,.<>?]{0,19}$/";
        if (!preg_match($patrn,$s)) return false;
        return true;
    }
        
     //校验是ip地址
     public function isIP ($s) //by zergling
     {
         $patrn='/^[0-9.]{1,20}$/';
         if (!preg_match($patrn,$s)) return false;
         return true;
     }
        
     //校验复合登录名：只能输入5-20个以字母开头、可带数字、“_”、“.”的字串  或 手机号 或email
     public function isComplexUserName ($s){
    	 $res = $this->isRegisterUserName($s) || $this->isMobil($s) || $this->isEmail($s);
    	 return res;
     }
}
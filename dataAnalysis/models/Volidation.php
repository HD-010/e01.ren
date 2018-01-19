<?php
namespace app\models;

class Volidation
{
    /**
     * 安全字符处理
     *
     */
    public function securityStr($str){
        $st = md5($str);
        $childst1 = $childst2 = '';
        for($i = 0;$i < count($st); $i ++ ){
            if($i%3){
                $childst1 .= $st[$i];
            }
            if($i%2){
                $childst2 .= $st[$i];
            }
        }
        $str = substr($childst1.$childst2,1,29);
        return md5($str);
    }
    
    
    /**
     * 生成口令
     * @param unknown $str
     * @return string
     */
    public function createToken($str){
        $lonstr = $len = $token = "";
        for($i = 0; $i < strlen($str); $i++){
            $lonstr .= $this->securityStr($str[$i]);
        }
        return strtoupper(md5($this->subtoken($lonstr)));
    }
    
    public function subtoken($str){
        $lonstr = $len = $token = "";
        for($i = 0; $i < strlen($str); $i++){
            if(!($i%3)){
                $len ++;
                $token .= $str[$i];
            }
        }
        if(strlen($token)/3 > 20){
            $str = $token;
            $token = $this->subtoken($str);
        }
        return $token;
    }
}


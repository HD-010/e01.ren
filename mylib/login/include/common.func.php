<?php
class Tools{
    static $temp = array();       //临时变量
    
    static function M(){
        return new Tools;
    }
    
    /**
     * 判断数组有效性,并添加state状态
     * @param unknown $array
     * @return number[]|unknown[]|number[]
     */
    static function isdata($array){
        if(is_array($array)){
            if(!empty($array)) {
                return ['state'=>1,'data'=>$array];
            }else{
                return ['state'=>0];
            }
        }
    }
    
    //返回数组中指定键名对应的所有值 
    static function arr_key_values($arr,$key){
        foreach($arr as $k=>$v){
           if($k === $key){
               Tools::$temp[]=$v;
           }
            if(is_array($v)){
                $arr =$v;
                self::arr_key_values($arr, $key);
            }
        }
        return Tools::$temp;
    }
}
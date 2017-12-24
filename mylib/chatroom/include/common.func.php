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
    
    /**
     * 返回合并后的数组
     * @param array $arr1   主数组
     * @param array $arr2   从数组
     * @param string $rule  合并的规则 id==id
     */
    static function combine_recordes($arr1,$arr2,$rule){
        //判断合并的条件 是否成立 
        $state = !$arr1['state'];
        if($state) return ['state'=>0];
        
        $state = !$arr2['state'];
        if($state) return $arr1;
        
        //解析合并规则
        //1、按相等合并
        $rule = explode('==', $rule);
        if(count($rule) > 1){
            $rule[2] = '==';
        }
        
        //按条件合并数组
        for($i = 0 ;$i < count($arr1['data']) ; $i ++){
            $condition1 = $arr1['data'][$i][$rule[0]];
            for($j = 0 ;$j < count($arr2['data']) ; $j ++){
                $condition2 = $arr2['data'][$j][$rule[1]];
                if($rule[2] == '=='){
                    if($condition1 == $condition2){
                        $arr1['data'][$i] +=$arr2['data'][$j];
                        continue;
                    }
                }
            
            }
            
        }
        return $arr1;
    }
    
    /**
     * 开启session
     */
    static function sessionstatar(){
        if(isset($_COOKIE['PHPSESSID'])){
            @session_id($_COOKIE['PHPSESSID']);
            @session_start();
        }else{
            @session_start();
        }
    }
    
    
    //创建规则
    static function processpws($str){
        $str = md5(md5($str));
        $stmp = '';
        
        for($i = 0 ;$i < strlen($str) ; $i ++){
            if($i%3){
                $stmp .= $str[$i];
            }
        }
        
        return substr($stmp,1,8);
    }
    
    
    /**
     * 批量格式化时间,用于isdata()处理过的查询返回的结果
     * @param unknown $res
     * @param unknown $fieldname
     * @return number[]|unknown
     */
    static function chage_foramttime($res,$fieldname){                            
        if(!$res['state']) return ['state'=>0];
        for($i = 0; $i < count($res['data']) ; $i ++){
            if($res['data'][$i][$fieldname] > 0){
                $res['data'][$i][$fieldname] = @date('m-d H:i:s',$res['data'][$i][$fieldname]);
            }
        }
        return $res;
    }
    
    /**
     * 格式 化文本
     * @param unknown $data
     */
    static function p($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    static function pe($data){
        self::p($data);exit;
    }
}
<?php
namespace app\components;

class T
{
    
    public static function arrayValue($key,$array,$default=null){
        $arr = $array;
        $keys = explode('.', $key);
        $error = '错误：不成以数组的形式访问字符串';
        $value = $default;
    
        if(!is_array($arr)) return $error;
        foreach ($keys as $val) {
            if (! is_array($arr)) {
                return $error;
            }
            $arr = array_key_exists($val, $arr) ? $arr[$val] : false;
            $value = $arr;
        }
        return $value ? $value : $default;
    }
    
    
    /**
     * 为数据添加status状态标识
     * @param unknown $data
     */
    public static function addStatus($data){
        if(empty($data)) return ['status' => 0];
        return array(
            'status' => 1,
            'data' => $data
        );
    }
    
    
    
    
    public static function outJson($data){
        //启用output buffering机制。 Output buffering支持多层次 — 例如，可以多次调用 ob_start() 函数。
        ob_start();
        
        //发送output buffer（输出缓冲）并禁用output buffering机制。
        ob_end_flush();
        
        //清除output buffer但不发送，并禁用output buffering。
        ob_end_clean();
        
        //将当前的output buffer返回成一个字符串。允许你处理脚本发出的任何输出。
        ob_get_contents();
        echo \GuzzleHttp\json_encode($data);
        
    }
    
    /**
     * @author 弘德誉曦
     * 查找多维数组中同一层级的所有相同键名的值
     *
     * @param string $key     查找的键名
     * @param array $array   被查找的多维数组
     * @param string $limter  字串分隔符
     * return string|array
     */
    public function implodeArr($key,$array,$limter=null){
        $data = $limter ? '' : [];
        if(!is_array($array)) return "";
        foreach($array as $k => $v){
            if(!is_array($v)) return "";
            if(array_key_exists($key,$v)){
                if($limter){
                    $data .= $limter . $v[$key];
                }else{
                    $data[] = $v[$key];
                }
    
            }else{
                if($limter){
                    $data .= $this->implodeArr($key,$v,$limter);
                }else{
                    $data = array_merge($data,$this->implodeArr($key,$v,$limter));
                }
            }
        }
        return $data;
    }
}
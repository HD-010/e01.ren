<?php
/**
 * php接口访问类
 * 接口说明：服务端返回json格式的数据，接口将其解析为php编码
 * @author yx010
 *
 * php接口访问类示例：
    $api = new Api();
    $url = 'http://localhost/mylib/index.php';      //访问路径
    $param = ['a'=>'01','b'=>'56'];                 //参数部份
    $data = $api->set_param($url,$param)->get_phpData();
    print_r($data);

    服务器端代码形式如：
    header("Content-Type:text/html;charset=UTF-8");
    echo json_encode('25555');
 *
 *
 */
class Api{
    public $url;
    public $o;
    
    /**
     * 设置参数
     * @param array $url  接口地址
     * @param array $o    携带的参数对象
     * @return object
     */
    public function set_param($url,$o){
        $this->url = trim($url);
        $this->o = $o;
        return $this->arr2param();
    }
    
    /**
     * 返回php对象
     */
    public function get_phpData(){
        $cphorder = file_get_contents($this->url);
        $cphorder = json_decode($cphorder);
        return $cphorder;
    }
    
    //将数组还原为键值对字符串的参数形式
    public function arr2param(){
        $str = "";
        foreach($this->o as $k => $v){
            $str .= '&'.trim($k).'='.trim($v);
        }
        //如果url带有? ,则说明url自身带有参数
        $url = (strpos($this->url,'?') !== false) ? $this->url . $str :  $this->url .'?'. substr($str, 1);
        $this -> url = $url;    
        return $this;
    }
}


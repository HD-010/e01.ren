<?php
namespace app\modules\edbug\models;

use \Yii;

class Parse
{
    
    /**
     * 解析应用入口文件
     * 思路：当用户访问本地的调试文件后，会向远程服务器发送请求， 由当前模块返回js代码，让其重定向到远程服务器
     */
    public function getMain(){
        $contents = file_get_contents(Yii::getAlias("@js/main.js"));
        echo $contents;
    }
    //查看日志
    public function getLayout(){
        $content = file_get_contents(Yii::getAlias('@app/modules/edbug/views/layouts/eBug.htm'));
        $content = str_replace("\r", '', $content);
        $content = str_replace("\n", '', $content);
        echo "var bodyContents ='". $content."'";
    }
    
    public function getEbug(){
        $contents = file_get_contents(Yii::getAlias('@app/assets/static/js/eBug.js'));
        echo $contents;
    }
    
    public function getEBugStyle(){
        $contents = file_get_contents(Yii::getAlias('@app/assets/static/js/eBugStyle.js'));
        echo $contents;
    
    }
     
    //获取视图小部件
    public function getWget(){
        global $data;
        $contents = file_get_contents(Yii::getAlias('@app/components/views/wget/'.$data.'.htm'));
        echo $contents;
    }
    
    //解析日志类型
    public function getParseFormate(){
        //$data = htmlspecialchars($data);
        $strHtml = $this->logFormate();
        echo $strHtml;
    }
    
    //格式化json代码
    public function getCodeFormate(){
        global $data;
        $data = json_decode($data);
        $strHtml = $this->eachElement($data);
        echo $strHtml;
    }
    
    //获取格式化后的代码列表
    public function getCodeList(){
        $strHtml = $this->eachElement();
        echo $strHtml;
    }
    
    //调试服务端功能扩展
    public function getFunc(){
        global $data;
        $code = [];
        $code['clear'] = '$this->clearLog();';
        header("Access-Control-Allow-Origin:*");
        echo htmlspecialchars($code[$data]);
    }
    
    //格式化日志为html代码
    public function logFormate(){
        global $data;
        $lines = explode('`p`', $data);
        $contents = "";
        //遍历数组整理数据
        foreach($lines as $line){
            $line = trim($line);
            $strStart = substr($line,0,1);
            $strEnd = substr($line,-1);
            if(($strStart == '{' && $strEnd== '}') ||
                ($strStart == '[' && $strEnd == ']')){
                    $contents .= "<div><span name='ft'>格式化代码</span><span name='code'>" . $line . "</span></div>\r\n";
            }else{
                $contents .= "<p>".$line."</p>\r\n";
            }
        }
        return $contents;
    }
    
    //格式化json代码
    public function eachElement($data) {
        $str = "";
        if (is_object($data)) {
            $str .= "{";
            foreach ($data as $k => $v) {
                $str .= "<span name=st>";
                if (!is_object($v) && !is_array($v)) {
                    $str .= "<font source=object>" . $k . " : ". $v . "</font>";
                } else {
                    $str .= "<font source=object>" . $k . ":" . $this->eachElement($v) . "</font>";
                }
                $str .= "</span>";
            }
            $str .= "}";
        } else if (is_array($data)) {
            $str .= "[";
            foreach ($data as $k => $v) {
                $str .= "<span name=st>";
                if (!is_object($v) && !is_array($v)) {
                    $str .= "<font source=array>" . $v	. "</font>";
                } else {
                    $str .= "<font source=array>" . $this->eachElement($v)	. "</font>";
                }
                $str .= "</span>";
            }
            $str .= "]";
        }
        return $str;
    }
}


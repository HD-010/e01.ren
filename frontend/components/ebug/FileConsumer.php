<?php

namespace app\components\ebug;

class FileConsumer extends AbstractConsumer {

    private $file_handler;
    private $log_path;

    public function __construct($filename) {
        $this->log_path = dirname($filename)."/ebug.log";
        $this->file_handler = fopen($filename, 'a+');
    }

    public function send($msg) {
        if ($this->file_handler === null) {
            return false;
        }
        return fwrite($this->file_handler, $msg . "\n") === false ? false : true;
    }

    public function close() {
        if ($this->file_handler === null) {
            return false;
        }
        $line = $this->check();
        if($line !== false){
            $this->send_to_server($line);
        }
        return fclose($this->file_handler);
    }
    
    /**
     * 检查发送需要
     * @return mixed
     */
    public function check(){
        if(!file_exists($this->log_path)){  //检查文件不存在则创建初始文件
            $this->init_log();
        } else {    //文件存在则读取文件内容，并判断是否需要发送日志内容，返回发送日志的开始行号
            $cont = file($this->log_path);
            if(($cont[1] + 5) < time()) {
                return (int)$cont[0];
            }
        }
        return false;
    }
    
    /**
     * 初始化日志发送记录
     * 设置上次读取日志 的最后一行行号为0
     * 设置上一次读取日志的时间当前时间戳
     * 没有返回值
     */
    public function init_log($line=1){
        $data = $line."\r\n";          //上一次读取日志的最后一行
        $data .= time();   //上一次读取日志的时间
        file_put_contents($this->log_path, $data);
    }
    
    /**
     * 读取日志内容
     * @param unknown $line
     * @return string[]
     */
    public function get_log_contents($line){
        $curent_line = 0;
        $max_lines = 200;
        $contets = [];
        //获取日志内容
        rewind($this->file_handler);
        while(($line_content = fgets($this->file_handler, 4096000)) !== false){
            if($curent_line < $line){
                $curent_line ++;
                continue;
            }
            if($curent_line > $max_lines){
                break;
            }
            $contets[] = $line_content;
            $curent_line ++;
        }
        $this->init_log($curent_line);
        return $contets;
    }
    
    /**
     * 发送日志内容
     * @param unknown $line
     */
    public function send_to_server($line){
        $contents = $this->get_log_contents($line);
        if(empty($contents)) return;
        $url = "http://data-analysis.e01.ren/?r=analysis/index/test";
        $res = EbugTranceData::curl_post($url,$contents);
    }
    
}

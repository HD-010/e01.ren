<?php
/**
 * 数据库连接助手
 * @author yx010
 *
 */
class Sqlhelper{
    //数据库连接信息
    public $dbhost = 'localhost';
    public $dbname = 'chatroom';
    public $dbuser = 'root';
    public $dbpwd = 'root';
    public $dbprefix = 'cph_';
    public $cfg_db_language = 'utf8';
    
    public $dsql;
    public $link;
    public $query;      //查询语句
    public $result;     //查询结果
    
    function __construct(){
        $this->link = mysql_connect($this->dbhost,$this->dbuser,$this->dbpwd) 
            or die('Could not connect:'.mysql_error());
        mysql_select_db($this->dbname) or die('Could not select database');
    }
    
    public function SetQuery($query){
        $this->query = $query;
    }
    
    //执行sql语句
    public function Execute(){
       $this->result = mysql_query($this->query) or die('Query failed:'.mysql_error());
    }
    
    //返回关联数组
    public function GetArray(){
       $rows = array();
       while($row = mysql_fetch_array($this->result,MYSQL_ASSOC)){
           $rows[] = $row;
       }
       return $rows;
    }
    
    public function ExecuteNoneQuery($query){
        $res = mysql_query($query) or die('Query failed:'.mysql_error());
        return $res;
    }
}

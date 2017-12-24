<?php
/**
 * 数据库操作类的使用步骤：
 * @author yx010
 * 1、配置数据库操作类文件所在的路径：$dbclasspath
 * 2、操作数据库需要继承该类
 * 
 * 在model中查询的示例如下：
 * select 查询：（获取首页商品顶级分类）
    public function get_typestop() {
        //"获取商品顶级分类";
        $this->db->selectobj[] = "cph_arctype,id,typename,typepic";
        //查找的条件
		 $this->db->requirements =" typename <> '' and issend = 1";//获取城市不是空的
        $this->db->orderway = "id asc";
        $res = $this->db->get_records();
        return $res;
    }
    
    insert into 插入：（向购物车加入商品信息）
	public function insert_cart($aid,$userid,$freeattr,$buynumber){
        $this->selectobj[] = "cph_shops_products, aid, userid, buynums, freeattr";
        $this->insert_value[] = $aid.",'".$userid."'".",'".$buynumber."'".",'".$freeattr."'";
        return $this->insert_records();
	}
	
	update 更新 :（将指定记录的readed设置 为1）
    public function readed($logid){
        $logid = implode(',', $logid);
        $this->db->selectobj[] = "chat_log, readed='1'";
        $this->db->requirements = "id in (".$logid.")";
        $res = $this->db->update_records();
        return $res;
    }
 */


class Model{
    public $dbclasspath = CHATINCLUDECPATH; //配置数据库操作类路径
    public $db;
    
    function __construct(){
        define('DBCLASSPATH',$this->dbclasspath);
        include $this->dbclasspath."/db.php";
        $this->db=new Db;
    }
}
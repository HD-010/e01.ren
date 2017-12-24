<?php
/**
 * 数据库操作类的使用步骤：
 * @author yx010
 * 1、数据库操作类包含文件：db.php、query.php、sqlhelper.php
 * 2、配置数据库操作类文件所在的路径：$dbclasspath
 * 3、在model中实例化该类。如：
 * class Model{
        public $dbclasspath = CHATINCLUDECPATH; //配置数据库操作类路径
        public $db;
    
        function __construct(){
            define('DBCLASSPATH',$this->dbclasspath);
            include $this->dbclasspath."/db.php";
            $this->db=new Db;
        }
    }
 * 4、操作数据库需要继承model类
 * 5、在model中查询的示例如下：
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


include DBCLASSPATH."/query.php";
class Db extends Query{
    //操作对象
    public $selectobj = array();
    
    //查找的条件
    public $requirements = "";
    public $orderway = "";
    public $limit = "";
    public $joinrequirements = "";
    
    //插入数据
    public $insert_value = array();
    
    
   
    function __construct(){
        parent::__construct();
    }
    
    //重置属性值
    public function cleardata(){
        $this->selectobj = array();
        $this->orderway = "";
        $this->joinrequirements = "";
        $this->limit = "";
        $this->requirements = "";
    }
    
    //获取查询记录
    public function get_records(){
        $this->set_selectparameters($this->selectobj,$this->requirements,$this->orderway,$this->limit,$this->joinrequirements);
        $this->query = $this->get_selectquery();
        //echo $this->query."<br/>";
        $this->dsql->SetQuery($this->query);
        $this->dsql->Execute();
        $rows = $this->dsql->GetArray();
        $this->cleardata();
        return $rows;
    }
    
    
    //插入新的记录
    public function insert_records($ac="get_insertquery"){
        $this->set_insertparameters($this->selectobj,$this->insert_value,$this->requirements);
        $query = $this->$ac();
        //return $query;exit;
        $this->selectobj = array();         //将数组初始化
        $this->insert_value = array();         //将数组初始化
        $inte=$this->dsql->ExecuteNoneQuery($query);
        $this->cleardata();
        return $inte;
    
    }
    
    //获取修改记录
    public function update_records(){
        $this->set_selectparameters($this->selectobj,$this->requirements,$this->orderway,$this->limit,$this->joinrequirements);
        $query = $this->get_updatequery();
        $res = $this->dsql->ExecuteNoneQuery($query);
        $this->cleardata();     //清空 数据
        return $res;
    
    }
    
    //获取删除记录
    public function delete_records(){
        $this-> set_deleteparameter($this->selectobj,$this->requirements);
        $this->query = $this->get_deletequery();
        // echo $this->query."<br/>";
        $this->selectobj = array();
        $res = $this->dsql->ExecuteNoneQuery($this->query);
        //echo $res;
        $this->cleardata();
        return $res;
    
    }
    
}

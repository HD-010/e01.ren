<?php
header("Content-Type:text/html;charset=utf-8");
/**
 * 组织sql语句
 * @author yx010
 *
 */
include DBCLASSPATH."/sqlhelper.php";
class Query{		
		public $dsql;
		public $selectobj;        //查找的表及其相关字段
		public $maintable;        //查询的主表
		public $requirements;     //查找的条件
 		public $fields;           //查找字段
		public $orderway;         //排序方式
		public $limit;            //限制查询条数
		public $joinrequirements;        //连接查询条件	
		public $jointabls = array();      //连接查询的表名称
		public $tababoutfields = array();     //表名与字段对应的数组
		public $insert_value;     //插入的数据
		
		function __construct(){
		    $this->dsql = new Sqlhelper;
		}
		/**
		 * 清空数据
		 */
		public function clearparam(){
		    $this->selectobj=$this->maintable=$this->requirements=$this->fields=$this->orderway=$this->limit=$this->joinrequirements=$this->insert_value='';
		    $this->jointabls=$this->tababoutfields=array();
		}
		/**
		 * 设置查询语句参数
		 */
		public function set_selectparameters($selectobj='',$requirements='',$orderway='',$limit='',$joinrequirements=''){
		    $this->selectobj = $selectobj;
    		$this->requirements = $requirements;     
    		$this->orderway = $orderway;         
    		$this->limit = $limit;            
    		$this->joinrequirements = $joinrequirements;
    		
    		$this->explodetablesandfields();        //将表名和字段名折分为对应 的关系
		}
		
		/**
		 * 设置插入语句参数
		 */
		public function set_insertparameters($selectobj='',$insert_value='',$requirements=''){
		    $this->selectobj = $selectobj;
		    $this->insert_value = $insert_value;
		    $this->requirements = $requirements;
		    $this->explodetablesandfields();        //将表名和字段名折分为对应 的关系
		    //print_r($this->tababoutfields);
		}
		

		/**
			设置删除语句参数
			 parameter $selectobj 需要删除的表名
			 parameter $requirements 需要删除的条件 
		*/
		public function set_deleteparameter($selectobj='',$requirements=''){
			 $this->selectobj = $selectobj;//获取删除的表名
			 $this->requirements = $requirements;//获取需要删除的条件
			 $this->explodetablesandfields(); 
		}

		


		/**
			获取delete语句
		*/

		public function get_deletequery(){
			//格式如下：
			/*delete ? from  where ? */
			$query = "delete from ". 
			$this->jointabls[0].   //删除的主表
			$this->set_requirements(); // 删除的条件
			$this->clearparam();
			return $query;
		}
		/**
		 * 获取select语句
		 */
        public function get_selectquery(){
            //格式如下：
            /* select ? 
            from ? 
            left join ? on ? 
            where ? 
            order by ? 
            limit ?; */
            $_SESSION['curenttable'] = $this->jointabls;
            
            //写入查询条件            
            if(strpos($this->requirements, '.') !== FALSE){     //处理多表查询条件
                $requirements = substr($this->requirements, strpos($this->requirements, '.') + 1);
            }else{$requirements = $this->requirements;}         //处理单表查询条件
            $_SESSION['requirements'] = $requirements;
            
    		$query = "select ".
            $this->set_fields().                      //设置查询语句中的字段
            $this->set_maintable().                   //查询的主表
    		$this->set_joinrequirements().            //连接查询条件
    		$this->set_requirements().                //设置查询条件
    		$this->set_orderway().
    		$this->set_limit();
    		$this->clearparam();
    		//echo $query."<br/>";
    		return $query;
        }
		
        
        /**
         * 获取show语句
         * 
         */
        public function get_showquery(){
            //格式如下：
            //SHOW COLUMNS FROM cph_member LIKE  'sex';
            $query = "SHOW ".
            $this->set_fields().                      //设置查询语句中的字段
            $this->set_maintable().                   //查询的主表
            $this->set_requirementsfield();           //设置查询条件
            $this->clearparam();
            return $query;
        }
        
        
        
        /**********************************************************/
        /**
         * 获取insert语句
         */
        public function get_insertquery(){
            //格式 如下;
            /* INSERT INTO 
                `cphv1.0`.`cph_archives` 
                (`typeid`, `typeid2`, `sortrank`, `flag`, `stretchid`, `ismake`, `channel`, `arcrank`, `click`, `money`, `title`, `number`, `shorttitle`, `color`, `writer`, `source`, `litpic`, `pubdate`, `senddate`, `mid`, `keywords`, `kucun`, `scores`, `goodpost`, `badpost`, `voteid`, `notpost`, `description`, `filename`, `dutyadmin`, `tackid`, `mtype`, `weight`, `state`)
                 VALUES 
                 ('78', '0', '0', '0', NULL, '0', '0', '1', '0', '0', '0', ' ', '0', ' ', ' ', ' ', ' ', ' ', '0', '0', '0', ' ', '0', '0', '0', '0', '1', '0', ' ', ' ', '0', '0', '0', '0', '1')";
            */
            $query = "INSERT INTO ". 
            $this->set_inserttable().                   //查询的主表
            $this->set_insertfields().                  //设置查询语句中的字段
            " VALUES ".
            $this->set_insertvalues().
            $this->set_requirements();
            $this->clearparam();
            return $query;
        }
        
        /*
         * 插入的表名
         */
        public function set_inserttable(){
            return $this->jointabls[0];
        }
        
        /**
         * 插入的字段名
         */
        public function set_insertfields(){
            $fieldsarr = $this->tababoutfields[0];
            $fields = "";
            for($i = 0;$i < count($fieldsarr); $i ++){
                $fields .= ", ".$fieldsarr[$i];
            }
            $fields = " (". substr($fields, 1) ." )";
            return $fields;
        }
        
        /**
         * 插入的字段对应的值 
         */
        public function set_insertvalues(){
            $valus = $this->insert_value[0];
            $valus = "(". $valus .")";
            return $valus;
        }
        
        
        
        
        /*****************************************************************************/
        /**
         * 获取update语句
         */
        public function get_updatequery(){
            //格式 如下；
            /* UPDATE tbl_name 
            SET col_name1=expr1 [, col_name2=expr2 ...] 
            [WHERE where_definition] 
            [ORDER BY ...] 
            [LIMIT row_count] */
            $query = "UPDATE ".
            $this->jointabls[0].
            " SET ".
            $this->set_updatefields().                  //设置查询语句中的字段
            $this->set_requirements();
            $this->clearparam();
            return $query;
        }
        
		/**
		 * 将表名和字段名折分为对应 的关系
		 */
		public function explodetablesandfields(){
		    $jointabnumber = count($this->selectobj);
		    for($i = 0;$i < $jointabnumber;$i ++){
		        $atableinfor = explode(",",$this -> selectobj[$i]);
		        $this -> jointabls[] =  $atableinfor[0];
		        $temparr = array();
		        for($j = 1;$j < count($atableinfor);$j ++){
		            $temparr[] = $atableinfor[$j];
		        }
		        $this -> tababoutfields[] = $temparr;
		    }
		    //print_r($this -> tababoutfields);
		}
		
		
		/**
		 * 设置查询字段
		 */
		public function set_fields(){
		    if(count($this -> jointabls) == 1){     //单张表查询
		        for($i = 0;$i < count($this->tababoutfields[0]);$i ++){
    		        $this -> fields .= ",".$this->tababoutfields[0][$i]." ";
		        }
		        $this -> fields = substr($this -> fields, 1);
		    }elseif(count($this -> jointabls) > 1){   //多张表查询
		        for($k = 0;$k < count($this->jointabls);$k++){
    		        for($i = 0;$i < count($this->tababoutfields[$k]);$i ++){
						//默认字段选项
						$fields = $this->jointabls[$k];
						if(stripos($this->jointabls[$k],' as ') !== false){
							$fields = substr($this->jointabls[$k],stripos($this->jointabls[$k],' as ') + 4);
						}
						
    		            $this -> fields .= ", ".$fields." . ".$this->tababoutfields[$k][$i]." ";
    		        }
		        }
		        $this -> fields = substr($this -> fields, 1);
		    }else{
		        exit("查询语句组织失败...");
		    }
		    return $this -> fields;
		}
		
		
		/**
		 * 设置查询的主表
		 */
		public function set_maintable(){
		    $this->maintable = " from ".$this->jointabls[0];
		    return $this->maintable; 
		}
		
		/**
		 * 设置查找的条件
		 */
		public function set_requirements(){
		    if($this->requirements != ""){
    		    $this->requirements =" where ". $this->requirements;
    		    return $this->requirements;
		    }
		}
		
		
		/**
		 * 设置show语句的条件字段
		 */
		public function set_requirementsfield(){
		    if($this->requirements != ""){
		        $this->requirements = $this->requirements;
		        return $this->requirements;
		    }
		}
		
		
		//设置update语句中字段与值 的对应关系
		public function set_updatefields(){
		    $fields = "";
		    for($i = 0 ;$i < count($this->tababoutfields[0]);$i ++){
		        $fields .= ",".$this->tababoutfields[0][$i] ;
		    }
		    $fields = substr($fields, 1);
		    return $fields;
		}
		
		
		/**
		 * 设置排序方式
		 */
		public function set_orderway(){
    		if (preg_match("/\bgroup\s*by\b/i", $this->orderway)) {
    		    return $this->orderway;
            }
		    if($this->orderway != ""){
		        $this->orderway = " order by ".$this->orderway;
    		    return $this->orderway;
		    }
		}
		
		
		/**
		 * 设置限制查询条数
		 */
		public function set_limit(){
		    if($this->limit != ""){
    		    $this->limit =" limit ". $this->limit;
    		    return $this->limit;
		    }
		}
		
		/**
		 * 设置连接查询条件	
		 */
		public function set_joinrequirements(){
		    $str = "";
		    if(!empty($this->joinrequirements)){
		        $joinrequirementsarr = explode(",",$this->joinrequirements);
    		    for($i = 0;$i < count($joinrequirementsarr);$i ++){
                    $str .= " left join ".$this->jointabls[$i + 1]." on ".$joinrequirementsarr[$i];
    		    }
		    }
		    $this->joinrequirements = $str;
		    return $this->joinrequirements;
		}
		
		
}



    //查找的表及其相关字段
    /* $selectobj = array();               
    $selectobj[] = "tabname1,fa1";
    $selectobj[] = "tabname2,fa2,fb2,fc2";
    $selectobj[] = "tabname3,fa3";
    //查找的条件
    $requirements ="id=35 and type=12 or other='false'";
    $orderway = "asc";
    $limit = "5,10";
    $joinrequirements = "f1=f1,fn=fx";
    
    $constructquery = new Constructquery;
    $constructquery->set_selectparameters($selectobj,$requirements,$orderway,$limit,$joinrequirements);
    //echo "<pre>";		
    print_r($constructquery ->get_selectquery()); */
    //echo "</pre>";
    
    //调用get_showquery（）方法范例:
    /* $selectobj = array();
    $selectobj[] = "cph_member,COLUMNS";
    $requirements =" LIKE  'sex'";
    $constructquery = new Constructquery;
    $constructquery->set_selectparameters($selectobj,$requirements);
    print_r($constructquery ->get_showquery()); */
?>
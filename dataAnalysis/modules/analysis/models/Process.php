<?php
namespace app\modules\analysis\models;

use app\modules\analysis\models\Schema;
use app\models\Validata;

/**
 * @author 弘德誉曦
 * 
 * 校验数据类型
 * 注：日期格式为（2018-01-10）
 *    日期时间格式为（2018-01-10 00:00:00）
 */
class Process
{
    public $data;       //待处理的数据
    public $properties; //事件或用户资料的属性名称
    public $type;       //事件类型
    public $time;       //事件发生时间
    public $distinct_id;//用户标识
    public $lib;        //使用的库
    public $project;    //项目名称
    public $event;      //事件名称
    public $tableName;  //当前事件类型对应的表名称
    public $moreFeilds; //新增属性
    /**
     * 将json转换为数组
     * @param string $jsonStr
     */
    public function initAnalysis($jsonStr){
        $char = mb_detect_encoding($jsonStr);
        $info = iconv($char, 'UTF-8', $jsonStr);
        $info = str_replace('\\','/',$info);
        $dataArr = json_decode($info,TRUE,512);
        $err = json_last_error();
        $this->data =  $err ? "json数据转换失败" : $dataArr;
        $this->properties = $this->data['properties'];
        $this->type = $this->data['type'];
        $this->time = $this->data['time'];
        $this->distinct_id = $this->data['distinct_id'];
        $this->lib = $this->data['lib'];
        $this->project = $this->data['project'];
        $this->event = $this->data['event'];
        $this->setTable2EventType();
        return $this;
    }
    
    
    
    /**
     * 设置事件类型与表的对照关系
     */
    public function setTable2EventType(){
        $table2EventType = [
            'track' => 'events',
            'profile_set' => 'users',
            'profile_set_once' => 'users',
            'track_signup' => 'users',
        ];
        $type = array_key_exists($this->type,$table2EventType) ? $this->type : '';
        //类型错误则谢绝入库
        if(!$type) {
            exit('事件类型错误');
            return;
        }
        $this->tableName =  $table2EventType[$type];
    }
    
    /**
     * 设置php数据类型与mysql数据类型的对照关系
     * @return string[]
     */
    public function getDataType2DataType(){
        return [
            'string' => 'varchar',
            'boolean' => 'tinyint',
            'integer' => 'float',
            'float' => 'float',
            'double' => 'float',
            'datetime' => 'datetime',
            'date' => 'date',
            'list' => 'text',
        ];
    }
    
    
    /**
     * 数据类型与字段长度的对照关系
     * @return string[]
     */
    public function getDataLength(){
        return [
            'varchar' => 'varchar(512)',
            'boolean' => 'boolean',
            'tinyint' => 'tinyint(1)',
            'float' => 'float',
            'datetime' => 'datetime',
            'date' => 'date',
            'text' => 'text',
        ];
    }
    
    
    /**
     * 获取事件追踪的基本属性
     * 注函数名称需满足的要求：getBase+事件类型名称（大写首字母）+Attr
     */
    public function getBaseTrackAttr(){
        return [
            'TIME' => $this->data['time'],
            'DISTINCT_ID' => $this->data['distinct_id'],
            '$LIB' => $this->data['lib']['$lib'],
            '$LIB_VERSION' => $this->data['lib']['$lib_version'],
            '$LIB_METHOD' => $this->data['lib']['$lib_method'],
            '$LIB_DETAIL' => $this->data['lib']['$lib_detail'],
            'EVENT' => $this->data['event'],
        ];
        
    }
    public function getBaseTrack_signupAttr(){
        return [
            'FIRST_ID' => $this->data['original_id'],
            'SECOND_ID' => $this->data['distinct_id'],
            'TIME' => $this->data['time'],
            '$LIB' => $this->data['lib']['$lib'],
            '$LIB_VERSION' => $this->data['lib']['$lib_version'],
            '$LIB_METHOD' => $this->data['lib']['$lib_method'],
            '$LIB_DETAIL' => $this->data['lib']['$lib_detail'],
        ];
    }
    
    //校验字段的有效性
    public function vaildFeilds(){
        //设置当前事件类型对应表的所有字段
        Schema::setTableDesc($this->tableName);
        
        //校验当前事件的所有属性是否在数据表中。如果所有字段都存在数据表中，则进一步校验字段的数据类型
        $valid = Schema::moreFeilds($this->getPropertiesName());
        if(empty($valid)){
            //比较同一属性名称，事件属性的数据类型和数据表字段的数据类型是否一致。如果一致则返回true(校验通过)，如果不一致则返回false
            return $this->comparEventDataType();
        }
        //如果有新的字段，则将新增字段的数据赋值给moreFeilds,并返回-1
        $this->moreFeilds = $valid;
        return -1;
    }
    
    /**
     * 获取待处理的数据
     * @return array 
     */
    public function getData(){
        return $this->data;
    }
    
    
    /**
     * 获取事件或用户资料中的Properties属性
     * return array 属性名属性值对
     */
    public function getProperties(){ 
        return $this->properties;
    }
    
    /**
     * 获取事件或用户资料中Properties属性的名称
     * return array 属性名的索引数组
     */
    public function getPropertiesName(){ 
        return array_keys($this->properties);
    }
    
    /**
     * 获取事件类型
     * return string  事件类型名称
     */
    public function getType(){
        return $this->type;
    }
    
    /**
     *获取事件发生时间 
     */
    public function getTime(){
        return $this->time;
    }
    
    /**
     * 获取用户唯一标识
     */
    public function getDistinct_id(){
        return $this->distinct_id;
    }
    
    /**
     * 获取用户接入数据的库
     */
    public function getLib(){
        return $this->lib;
    }
    
    /**
     * 获取项目名称
     */
    public function getProject(){
        return $this->project;
    }
    
    /**
     * 获取事件名称
     */
    public function getEvent(){
        return $this->event;
    }
    
    /**
     * 返回新增属性的名称及属性数据类型的对应关系
     * return array
     */
    public function getMoreFeilds(){
        return $this->getFeildsDataType($this->moreFeilds);
    }
    
    /**
     * 返回全部属性的名称及属性数据类型的对应关系
     * return array
     */
    public function getFullFeilds(){
        return $this->getFeildsDataType($this->getPropertiesName());
    }
    
    
    /**
     * 返回储存方式名称 
     */
    public function Storage(){
        $storageType = [
            'track' => 'insert',
            'track_signup' => 'insert',
            'profile_set' => 'update',
            'profile_set_once' => 'update'
        ];
        return $storageType[$this->type];
    }
    
    
    /**
     * 返回事件属性的名称及属性数据类型的对应关系
     * 
     * @param unknown $feilds
     */
    public function getFeildsDataType($feilds){
        $feild = array();
        $validata = new Validata();
        for($i = 0; $i < count($feilds); $i ++){
            //优先获取日期对象和时间日期对象
            if($validata->date($this->properties[$feilds[$i]])){
                $feild[$feilds[$i]] = 'date';
                continue;
            }
            if($validata->dateTime($this->properties[$feilds[$i]])){
                $feild[$feilds[$i]] = 'datetime';
                continue;
            }
            $feild[$feilds[$i]] = gettype($this->properties[$feilds[$i]]);
        }
        return $feild;
    }
   
    
    /**
     * 将事件属性数据类型的名称统一为数据库的数据类型名称
     * 
     * @return array
     */
    public function unanimousTypeName(){
        $feildDataType = $this->getFullFeilds();
        $type2type = $this->getDataType2DataType();
        $temp = array();
        foreach($feildDataType as $k => $v){
            $temp[$k] = trim($type2type[$v]);
        }
        return $temp;
    }
    
    
    /**
     * 比较同一属性名称，事件属性的数据类型和数据表字段的数据类型是否一致。
     * 如果一致则返回true(校验通过)，如果不一致则记录错误信息到$this->data[error],并返回false
     * @return boolean
     */
    public function comparEventDataType(){
        //事件的全部属性名称及属性数据类的对应关系
        $eventDataType = $this->unanimousTypeName();
        //数据表字段的数据类型
        $tableFeilds = Schema::getDataType();
        
        foreach($eventDataType as $k => $v){
            if($v !== $tableFeilds[$k]){
                $this->data['error'] = "数据类型错误";
                return false;
            }
        }
        return true; 
    }
    
    /**
     * 将新增属性转换为sql语句
     * 
     * return 返回需要插入的字段名组成的语句
     */
    public function feilds2sql(){
        $addContent = "";
        foreach($this->getMoreFeilds() as $name => $value){
            //定义 的数据类型与数据库中数据类型的对照关系
            $dataType = $this->getDataType2DataType();
            //数据类型与字段长度的对照关系
            $dataLength = $this->getDataLength();
            //组装新增字段的长度
            $clumAttr = $dataLength[$dataType[$value]];
            $addContent .= ", ADD $name $clumAttr";
        }
        $addContent = substr($addContent,1);
        return $addContent;
    }
    
    
    public function validData2sql(){
        return $this->{$this->storage().'ValidData2sql'}();
    }
    
    /**
     * 将有效数据转换为sql语句
     *
     * return 返回需要插入的字段名组成的语句
     * 格式如 ： name = value,name = value....
     */
    public function updateValidData2sql(){
        return;
    }
    
    /**
     * 将有效数据转换为sql语句
     * 
     * return 返回需要插入的字段名组成的语句 
     * 格式如 ： (`name`,`info`)  VALUE ('name',65)
     */
    public function insertValidData2sql(){
        //将properties转换为sql
        $names = "(";
        $values = "(";
        $validData = $this->getValidData();
        foreach($validData as $feild => $value){
            //拼接需要插入的字段名称
            $names .= '`' . $feild . '`,';     
            //拼接需要插入的与字段名称对应的值,如果是数值或boolen类型则不加单引号
            $feildDataType = $this->getFullFeilds();
            //指定这些类型的值不加引号
            $forNumber = ['float','double','integer'];
            $tag = false;
            if(array_key_exists($feild,$feildDataType)){
                $tag = in_array($feildDataType[$feild], $forNumber);
            }
            $values .= $tag ? $value."," :  "'".$value."'," ;
        }
        $names = substr($names,0,-1);
        $values = substr($values,0,-1);
        $names .= ")";
        $values .= ")";
        return $names ." VALUE ". $values;
    }
    
    /**
     * 字段检验和数据校验后获取有效数据
     */
    public function getValidData(){
        $validData = $this->properties;         
        $baseAttr = $this->{'getBase'.ucfirst($this->type).'Attr'}();
        return array_merge($validData,$baseAttr);
    }
    
    /**
     * 将无效数据转换为sql语句
     *
     * return 返回需要插入的字段名组成的语句
     * 格式如 ： (`contents`,`datatype`,`addtime`)  VALUE ('jsonStr','效验失败','2018-01-12')
     */
    public function inValidData2sql($type = 1){
        $dataStr = \GuzzleHttp\json_encode($this->data);
        $dataStr = htmlspecialchars($dataStr);
        return "(`contents`,`datatype`,`addtime`)  VALUE ('".$dataStr."','".$type."','".date('Y-m-d H:i:s',time())."')";
    }
    
    /**
     * 返回有效数据入库失败的sql
     *
     * return 返回需要插入的字段名组成的语句
     * 格式如 ： (`contents`,`datatype`,`datatime`)  VALUE ('jsonStr','入库失败','2018-01-12')
     */
    public function insertError2sql(){
        return $this->inValidData2sql(2);
    }
    
}


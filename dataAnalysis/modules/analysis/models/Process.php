<?php
namespace app\modules\analysis\models;

use Yii;
use app\modules\analysis\models\Schema;
use app\models\Validata;
use app\components\T;
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
    public $serverName; //客户端域名 
    public $event;      //事件名称
    public $tableName;  //当前事件类型对应的表名称
    public $moreFeilds; //新增属性
    /**
     * 将json转换为数组
     * @param string $jsonStr
     */
    public function initAnalysis($jsonStr){
        $this->data = $this->formaterData($jsonStr);
        $this->properties = T::arrayValue('properties', $this->data,'');
        $this->type = T::arrayValue('type', $this->data,'');
        $this->time = T::arrayValue('time', $this->data,'');
        $this->distinct_id = T::arrayValue('distinct_id', $this->data,'');
        $this->lib = T::arrayValue('lib', $this->data,'');
        $this->project = T::arrayValue('project', $this->data,'');
        $this->serverName = Yii::$app->request->post('serverName','');
        $this->event = T::arrayValue('event',$this->data,"");
        $this->setTable2EventType();
        return $this;
    }
    
    /**
     * 格式化json数据
     * @param unknown $data
     * @return string|mixed
     */
    public function formaterData($data){
        $char = mb_detect_encoding($data);
        $info = iconv($char, 'UTF-8', $data);
        $info = str_replace('\\','/',$info);
        $dataArr = json_decode($info,TRUE,512);
        $err = json_last_error();
        $data =  $err ? "json数据转换失败" : $dataArr;
        return $data;
    }
    
    /**
     * 设置事件类型与表的对照关系
     * 表名字串则：客户端域名（不带点） + 项目名称  + 属性对应名称
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
        
        //表名字串则：客户端域名（不带点） + 项目名称  + 属性对应名称
        $servername = str_replace('.', '', $this->serverName);
        if(!$servername) return;
        if(!$this->project) return;
        $prefix = $servername ."_".$this->project."_";
        $this->tableName = $prefix.$table2EventType[$type];
        Schema::$prefix = $prefix;
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
    public function getBaseProfile_setAttr(){
        return [];
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
    
    /**
     * 将有效数据转换为sql语句
     * 
     * 返回sql语句的子串
     */
    public function validData2sql(){
        //在获取sql语句前进行校验，如果校验失败则返回 false
        if(!$this->{'valid'.ucfirst($this->Storage()).'Befor'}()) return false;
        return $this->{$this->storage().'ValidData2sql'}();
    }
    
    /**
     * 添加记录之前校验
     *
     */
    public function validInsertBefor(){
        return true;
    }
    
    /**
     * 更新之前校验用户资料是否已经存在（如果用户资料不存在则不更新资料）
     * 
     * 如果用户资料已经存在，则返回true,反之false
     * return boolen
     */
    public function validUpdateBefor(){
        return Schema::validUpdateBefor($this->distinct_id);
    }
    
    /**
     * 将有效数据转换为sql update语句
     *
     * return 返回需要插入的字段名组成的语句
     * 格式如 ： name = value,name = value....
     */
    public function updateValidData2sql(){
        $str = "";
        $validData = $this->getValidData();
        foreach($validData as $feild => $value){
            //拼接需要插入的与字段名称对应的值,如果是数值或boolen类型则不加单引号
            $feildDataType = $this->getFullFeilds();
            //指定这些类型的值不加引号
            $forNumber = ['float','double','integer'];
            $tag = false;
            if(array_key_exists($feild,$feildDataType)){
                $tag = in_array($feildDataType[$feild], $forNumber);
            }
            $value = $tag ? $value."," :  "'".$value."'," ;
            //拼接需要插入的字段名称
            $str .= '`' . $feild . '` = ' . $value ;
        }
        $str = substr($str,0,-1);
        $str .= " where second_id ='" . $this->distinct_id . "'";
        return $str;
    }
    
    /**
     * 将有效数据转换为sql insert语句
     *
     * return 返回需要插入的字段名组成的语句
     * 格式如 ： (`name`,`info`)  VALUE ('name',65)
     */
    public function insertValidData2sql(){
        //将properties转换为sql
        $names = "(";
        $values = "(";
        $validData = $this->getValidData();
        $tableDesc = Schema::$tableDesc;
        for($i = 0; $i < count(Schema::$tableDesc); $i ++){
            $name = Schema::$tableDesc[$i]['Field'];
            //拼接需要插入的字段名称
            $names .= '`' . $name . '`,';
            
            if(array_key_exists($name,$validData)){
                //拼接需要插入的与字段名称对应的值,如果是数值或boolen类型则不加单引号
                $feildDataType = $this->getFullFeilds();
                //指定这些类型的值不加引号
                $forNumber = ['float','double','integer'];
                $tag = false;
                if(array_key_exists($name,$feildDataType)){
                    $tag = in_array($feildDataType[$name], $forNumber);
                }
                $values .= $tag ? $validData[$name]."," :  "'".$validData[$name]."'," ;
                
            }else{
                $values .= "null,";
            }
        }
        $names = substr($names,0,-1);
        $values = substr($values,0,-1);
        $names .= ")";
        $values .= ")";
        if(Schema::$data){
            Schema::$data .= ",".$values;
        }else{
            Schema::$data = $names ." VALUES ". $values;
        }
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


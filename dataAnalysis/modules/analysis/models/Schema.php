<?php
namespace app\modules\analysis\models;

use Yii;
use app\modules\analysis\models\Process;


/**
 * @author w
 *
 */
class Schema
{
    public static $tableName;
    public static $tableDesc;
    
    //-- 判断 vrv_paw_rule 表是否存在 thresholdMin 字段，不存在则添加; 存在则修改字段类型
    /* $sql = "DELIMITER ??
    DROP PROCEDURE IF EXISTS schema_change??
    CREATE PROCEDURE schema_change()
    BEGIN
    IF NOT EXISTS (SELECT * FROM information_schema.columns WHERE table_schema = DATABASE()  AND table_name = 'test' AND column_name = 'number')  
    THEN
    ALTER TABLE test ADD COLUMN number  BIGINT;
    ELSE
    ALTER TABLE test MODIFY COLUMN name BIGINT ;
    END IF;
    END??
    DELIMITER ;
    
    CALL schema_change();"; */
    
    //如果在表中存在某个字段则修改其对应的值，如果不存在则添加这个字段，然后修改这个字段的值。完成后创建一条空记录
        
        
    //校验步骤：
    /** 
     * 1、每个用户的每张表的字段名对应一个集合
     * 将表中的字段信息保存到缓存中，（先保存到静态变量）
     */
    public static function setTableDesc($tableName){
        //如果当前表结构已经存在则不再往数据库查询
        if(self::$tableName == $tableName) return;
        self::$tableName = $tableName;
        self::$tableDesc = Yii::$app->db->createCommand("desc $tableName")
        ->queryAll();
    }
        
    
    
    /**
     * 2、判断需要插入的字段是否在数据表中完全有对应的字段
     * return blooen 如果有有对应的字段则返回true,如果没有则按添加相应的字段,如果添加成功返回true,如果添加失败返回fasle
     * 校验所有字段是否都存在及数据类型是否一致，如果存在且数据类型一致则直接插入新记录。如果不存在，则以当前数据的数据类型作为字段类型添加新字段。
     */
    public static function moreFeilds($feilds){
        //记录表结构中没有的字段，即为新增的属性。
        $moreFeilds = [];
        for($i = 0; $i < count($feilds) ;$i ++){
            $isExists = false;      //假设字段不存在
            for($j = 0; $j < count(self::$tableDesc); $j ++){
                if(self::$tableDesc[$j]['Field'] === $feilds[$i]){
                    $isExists = true;
                    break;
                }
            }
            //在表结构中没有找到当前字段，则将其记录到moreFeilds中的
            if(!$isExists){
                $moreFeilds[] = $feilds[$i];
            }
        }
        return $moreFeilds;
    }
    
    /**
     * 3、将新增字段添加到对应的表中
     * @param array $feilds 新字段及定义 的数据类型
     * return true 表示成功
     */
    public static function addFeilds($addContent){
        //组装字段内容到sql
        $sql = "ALTER table ".self::$tableName." $addContent";
        $conn = Yii::$app->db;
        return $conn->createCommand($sql)->execute(); //返回1则插入成功
    }
    
    
    
    /**
     * 一个首次出现的字段需要获取它的字符类型。 
     * 返回数据表中所有字段的数据类型
     */
    public static function getDataType(){
        $dataType = array();
        foreach(self::$tableDesc as $v){
            //数据类型的字符串
            $type = substr($v['Type'], 0,stripos($v['Type'], '('));
            $dataType[$v['Field']] = trim($type);
        }
        return $dataType;
    }
    
    /**
     * 插入通过校验的数据到对应的数据表中
     * @param string $data sql子串
     */
    public static function insertValidDAta($data){
        //$sql = "INSERT INTO tablename (name,info)  VALUE ('5',65)";
        $sql = "INSERT INTO `" + self::$tableName+ "` " + $data;
        $conn = Yii::$app->db;
        return $conn->createCommand($sql)->execute();
    }
    
    /**
     * 插入未通过校验的数据到errors数据表中
     * @param string $data sql子串
     */
    public static function insertInValidDAta($data){
        $sql = "INSERT INTO errors " + $data;
        $conn = Yii::$app->db;
        return $conn->createCommand($sql)->execute();
    }
        
}


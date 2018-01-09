<?php
namespace app\modules\analysis\models;

use Yii;

class Schema
{
    public static $userDesc;
    public static $eventsDesc;
    
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
     * 1、将表中的字段保存到缓存中，
     */
    public function setTableDesc($tableName){
        $res = Yii::$app->db->createCommand("desc $tableName")
        ->queryAll();
        $descName = $tableName.'Desc';
        Schema::$$descName = $res;
    }
        
    /**
     * 2、每个用户的每张表的字段名对应一个集合
     */
    
    /**
     * 3、校验所有字段是否都存在及数据类型是否一致，如果存在且数据类型一致则直接插入新记录。如果不存在，则以当前数据的数据类型作为字段类型添加新字段。
     */
    
    /**
     * 4、一个首次出现的字段需要获取它的字符类型。 
     */
    
    /**
     * 5、然后插入新记录。 
     */
        
}


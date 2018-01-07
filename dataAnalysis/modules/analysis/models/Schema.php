<?php
namespace modules\analysis\models;

class Schema
{
    public function a(){
        //-- 判断 vrv_paw_rule 表是否存在 thresholdMin 字段，不存在则添加; 存在则修改字段类型
        "DELIMITER ??
        DROP PROCEDURE IF EXISTS schema_change??
        CREATE PROCEDURE schema_change()
        BEGIN
        IF NOT EXISTS (SELECT * FROM information_schema.columns WHERE table_schema = DATABASE()  AND table_name = 'vrv_paw_rule' AND column_name = 'thresholdMin') THEN
        ALTER TABLE vrv_paw_rule ADD COLUMN thresholdMin  BIGINT;
        ELSE
        ALTER TABLE vrv_paw_rule MODIFY COLUMN thresholdMin BIGINT ;
        END IF;
        END??
        DELIMITER ;
        
        CALL schema_change();";
    }
}


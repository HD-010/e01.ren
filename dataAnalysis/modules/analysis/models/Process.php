<?php
namespace app\modules\analysis\models;

class Process
{
    /**
     * 将json转换为数组
     * @param string $jsonStr
     */
    public function ParseJson2arr($jsonStr){
        $char = mb_detect_encoding($jsonStr);
        $info = iconv($char, 'UTF-8', $jsonStr);
        $dataArr = json_decode($info,TRUE);
        $err = json_last_error();
        return $err ? "json数据转换失败" : $dataArr ;
    }
}


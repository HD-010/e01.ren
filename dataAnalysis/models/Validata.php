<?php
namespace app\models;

/**
 * 用户输入规则验证类
 * author   弘德誉曦 
 * date     2018-01-07
 * 
 */
class Validata
{

    // 验证是否为空
    public function required($str){
        if(trim($str) != "") return true;
        return false;
    }

    // 验证邮件格式
    public function email($str){
        if(preg_match("/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/", $str)) return true;
        else return false;
    }

    // 验证身份证
    public function idcode($str){
        if(preg_match("/^\d{14}(\d{1}|\d{4}|(\d{3}[xX]))$/", $str)) return true;
        else return false;
    }

    // 验证http地址
    public function http($str){
        if(preg_match("/[a-zA-Z]+:\/\/[^\s]*/", $str)) return true;
        else return false;
    }

    //匹配QQ号(QQ号从10000开始)
    public function qq($str){
        if(preg_match("/^[1-9][0-9]{4,}$/", $str)) return true;
        else return false;
    }

    //匹配中国邮政编码
    public function postcode($str){
        if(preg_match("/^[1-9]\d{5}$/", $str)) return true;
        else return false;
    }

    //匹配ip地址
    public function ip($str){
        if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $str)) return true;
        else return false;
    }

    // 匹配电话格式
    public function telephone($str){
        if(preg_match("/^\d{3}-\d{8}$|^\d{4}-\d{7}$/", $str)) return true;
        else return false;
    }

    // 匹配手机格式
    public function mobile($str){
        if(preg_match("/^(13[0-9]|15[0-9]|18[0-9])\d{8}$/", $str)) return true;
        else return false;
    }

    // 匹配26个英文字母
    public function en_word($str){
        if(preg_match("/^[A-Za-z]+$/", $str)) return true;
        else return false;
    }

    // 匹配只有中文
    public function cn_word($str){
        if(preg_match("/^[\x80-\xff]+$/", $str)) return true;
        else return false;
    }

    // 验证账户(字母开头，由字母数字下划线组成，4-20字节)
    public function user_account($str){
        if(preg_match("/^[a-zA-Z][a-zA-Z0-9_]{3,19}$/", $str)) return true;
        else return false;
    }

    // 验证数字
    public function number($str){
        if(preg_match("/^[0-9]+$/", $str)) return true;
        else return false;
    }
    
    // 验证日期
    public function date($str){
        $reg = '/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/';
        if(preg_match($reg, $str)) return true;
        else return false;
    }
    
    // 验证日期时间
    public function dateTime($str){
        $reg = '/^[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d:[0-5]\d$/';
        if(preg_match($reg, $str)) return true;
        else return false;
    }
}


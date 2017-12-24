<?php
class Chatlog extends Model{
    
    //获取未读聊天记录
    public function newlog(){
        $this->db->selectobj[] = 'chat_log,*';
        $this->db->requirements = " readed='0'";
        $res = $this->db->get_records();
        return Tools::isdata($res);
    }
    
    //将指定记录的readed设置 为1
    public function readed($logid){
        $logid = implode(',', $logid);
        $this->db->selectobj[] = "chat_log, readed='1'";
        $this->db->requirements = "id in (".$logid.")";
        $res = $this->db->update_records();
        return $res;
    }
    
    //向服务器写入聊天信息
    public function insertlog(){
        global $fromid;global $toid;global $chatcontent;
        $sendtime = @date(time());
        $this->db->selectobj[] = "chat_log,fromid,toid,sendtime,content";
        $this->db->insert_value[] = "'".$fromid."','".$toid."','".$sendtime."','".$chatcontent."'";
        $res = $this->db->insert_records();
        return $res;
    }
}

<?php
class Chatlog extends Model{
    
    //获取未读聊天记录
    public function newlog(){
        global $fromuin;global $touin;
        $this->db->selectobj[] = ' chat_log,*';
        $this->db->selectobj[] = ' chat_member,nickname';
        $this->db->joinrequirements = ' chat_member.id=chat_log.fromid';
        $this->db->requirements = " readed='0' and (toid='{$fromuin}' and fromid='{$touin}')";
        $res = $this->db->get_records();
        return Tools::chage_foramttime(Tools::isdata($res), 'sendtime');
    }
    
    //将指定记录的readed设置 为1
    public function readed($logid){
        global $fromuin;global $touin;
        $logid = implode(',', $logid);
        $childsql = $this->db->childsql($logid, 'id', 'or');
        $this->db->selectobj[] = "chat_log, readed='1'";
        $this->db->requirements = "toid={$fromuin} and fromid={$touin} and ({$childsql})";
        $this->db->update_records();
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

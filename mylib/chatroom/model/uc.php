<?php

class Uc extends Model{
    
    /**
     * 根据注册手机号或注册邮箱获取用户信息
     * @return number[]|unknown[]
     */
    public function get_userinfor(){
        global $mid;
        $findfeild  = Verification::inst()->ver_pre('isMobil',$mid) ? 'mobile' : 'email';
        $requirements = "$findfeild = '{$mid}'";
        
        $this->db->selectobj[] = 'chat_member,*';
        $this->db->requirements = $requirements;
        $this->db->limit = 1;
        return Tools::isdata($this->db->get_records()) ;
    }
    
    /**
     * 根据用户id获取好友信息
     */
    public function get_uinsinfo(){
        global $fromuin;
        $this->db->selectobj[] = 'chat_addonmember_uins,*';
        $this->db->requirements = "id = '{$fromuin}'";
        $this->db->limit = '0,1';
        $res = $this->db->get_records();
        return Tools::isdata($res);
    }
    
    /**
     * 获取好友列表
     * @param  $uinsid
     */
    public function get_uinslist($uinsid){
        $childsql = $this->db->childsql($uinsid, 'id', 'or');
        $this->db->selectobj[] = 'chat_member ,*';
        $this->db->requirements = $childsql;
        $this->db->orderway = ' id desc';
        $this->db->limit = '0,10';
        return Tools::isdata($this->db->get_records()) ;
    }
    
    /**
     * 获取好友的最后一条未读取的聊天信息
     * @param  $uinsid
     */
    public function get_onechatinfo($uinsid){
        global $fromuin;
        $childsql = $this->db->childsql($uinsid, 'fromid', 'or');
        $this->db->selectobj[] = 'chat_log ,distinct toid,max(id),fromid,content,sendtime';
        $this->db->requirements = "toid='{$fromuin}' and readed = '0' and " . '('.$childsql.')' ;
        $this->db->orderway = '  group by toid';
        return Tools::chage_foramttime(Tools::isdata($this->db->get_records()),'sendtime') ;
    }
    
    /**
     * 获取未读消息条数
     * @param unknown $uinsid
     */
    public function get_readingnum($uinsid){
        global $fromuin;
        $childsql = $this->db->childsql($uinsid, 'fromid', 'or');
        $this->db->selectobj[] = 'chat_log ,count(toid) as number,fromid';
        $this->db->requirements = " toid='{$fromuin}' and readed = '0' and " . '('.$childsql.')' ;
        $this->db->orderway = '  group by toid';
        $res = $this->db->get_records();
        return $this->chage_numstate(Tools::isdata($res)) ;
    }
    public function chage_numstate($res){                            //修改未读信息条数的状态
        if(!$res['state']) return ['state'=>0];
        for($i = 0; $i < count($res['data']) ; $i ++){
            if($res['data'][$i]['number'] > 99){
                $res['data'][$i]['number'] = '99+';
            }
        }
        return $res;
    }
    
    
}
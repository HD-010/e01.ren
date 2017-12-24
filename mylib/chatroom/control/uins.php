<?php
class Uins extends Control{

    public function ac_uins(){
        global $fromuin;
        $uc = $this->model('uc');
        
        $uins = $uc -> get_uinsinfo();                  //从chat_addonmember_uins获取好友信息  
        
        if(!$uins['state']) {return ['state'=>0];}  //空数据返回
        
        $uinsnum = $uins['data'][0]['friendsnumber'];   //获取好友数量
        $uinsid = $uins['data'][0]['friendid'];         //获取好友id
        
        //从chat_member获取好友列表
        $uinslist = $uc -> get_uinslist($uinsid);
        
        //获取最后一条聊天记录
        $chatlist = $uc -> get_onechatinfo($uinsid);
        
        //获取未读消息数量
        $readingnum =  $uc -> get_readingnum($uinsid);
        
        //合并好友列表
        $friendslist = Tools::combine_recordes($uinslist,$chatlist,'id==fromid');
        $friendslist = Tools::combine_recordes($friendslist,$readingnum,'id==fromid');
        
        return $friendslist;
    }
    
}
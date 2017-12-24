<?php

class Chat extends Control{
    
    //读取聊天记录（并设置该记录为已读）
    public function ac_chatlog(){
        //读取聊天记录
        $chatlog = $this->model('chatlog');
        $res = $chatlog->newlog();
        //提取记录中id值
        $logid = Tools::arr_key_values($res, 'id');
        // 设置记录为已读
        if(!empty($logid)){
            $chatlog->readed($logid);
        } 
        return $res;
    }
    
    //发送信息
    public function ac_sendinfo(){
        $chatlog = $this->model('chatlog');
        $res = $chatlog->insertlog();
        return $res;
    }
}



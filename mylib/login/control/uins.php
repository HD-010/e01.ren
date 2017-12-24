<?php
class Uins extends Control{

    public function ac_uins(){
        global $fromuin;
        $res = [['friendsid'=>'10000','face'=>'./static/images/sys/1.gif','remind'=>'22','nickname'=>'小龙女 ','news'=>'最后一天，错过再等一年','newstime'=>'22:20'],
				['friendsid'=>'10001','face'=>'./static/images/sys/4.gif','remind'=>'99+','nickname'=>'土豆豆 ','news'=>'家乡的味道远去','newstime'=>'21:20']];
        return $res;
    }
    
}
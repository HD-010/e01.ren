<?php


require_once CHATINCLUDECPATH."/common.func.php";
require_once CHATINCLUDECPATH."/verification.php";      
require_once CHATINCLUDECPATH."/control.php";
require_once CHATROOMCONTROLPATH."/".$ct.".php";
require_once CHATINCLUDECPATH."/model.php";

$ucct = ucfirst($ct);
$$ct = new $ucct;   //实例化当前控制器

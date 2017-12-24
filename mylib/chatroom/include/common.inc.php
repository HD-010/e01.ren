<?php
date_default_timezone_set('Asia/Shanghai');
define('CHATINCLUDECPATH',dirname(__FILE__));
define('CHATPATH',substr(CHATINCLUDECPATH,0,-8));
define('CHATROOMMODELPATH',CHATPATH.'\model');
define('CHATROOMSTATICPATH',CHATPATH.'\static');
define('CHATROOMCONTROLPATH',CHATPATH.'\control');

extract($_REQUEST); //将变量从数组中导入当前的符号表中

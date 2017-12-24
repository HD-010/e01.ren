<?php

include_once dirname(__FILE__)."/include/common.inc.php";
include_once CHATINCLUDECPATH."/require.php";

//输出数据
header("Cache-Control:no_cache");
echo json_encode($$ct -> {'ac_'.$ac}());

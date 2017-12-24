<?php
header("content-type:text/html;charset=utf-8");
include "./edit/edit.class.php";
$edit = new Edit;
$edit->view([
    'etitle'=>'标题',
    'comment'=>'注释',
    'value'=>'默认值',
    'position'=>'left',
    'type'=>'text',
    'name'=>'myname',
    'class'=>'test'       //类名
],"li");
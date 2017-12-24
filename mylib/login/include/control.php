<?php
class Control{
    //实例化model类
    public function model($model){
        require_once CHATROOMMODELPATH.'/'.$model.'.php';
        $model = ucfirst($model);
        return new $model;
    }
}
<?php
/**
 * 表单元素类基于html5(多用于后台编程)
 * @author yx010
 * 类与edit/model相对路径不能改变
 */
class Edit{
    public $modelpath;      //model路径
    
    //初始化...
    function __construct(){
        //设置model路径
        $mypath = substr(__FILE__,0,strripos(__FILE__, '\\'));
        define('EDITMODELPATH',$mypath.'\model');
    }
    
    //实例化model类
    public function model($model){
        include EDITMODELPATH.'/'.$model.'.php';
        $model = ucfirst($model);
        return new $model;
    }
    
    
    /***************以下为应用类函数*****************/
    
    //input输入框
    public function input($attribute){
        $parseattribute = $this->model('input');
        $o = $parseattribute->set_attr($attribute)->element();
    }
    
    //select输入框
    public function select($attribute){
        $parseattribute = $this->model('select');
        $o = $parseattribute->set_attr($attribute)->element();
    }
    
    //textarea输入框
    public function textarea($attribute){
        $parseattribute = $this->model('textarea');
        $o = $parseattribute->set_attr($attribute)->element();
    }
    
    //view输入框
    public function view($attribute,$tag){
        $parseattribute = $this->model('view');
        $o = $parseattribute->set_attr($attribute,$tag)->element();
    }
}

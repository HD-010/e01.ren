<?php
class Parseattribute{
    public $sttrarr;    //输入框属性
    public $tag;        //标签名称
    public $attribute;  //输入框属性（字串）
    public $element;    //元素名称
    public $isnotattr;  //非元素属性
    
    
    /**
     * 获取对应输入框属性设置函数
     * @param array $attribute 输入框属性
     * position:为标题所在的位置left|right。默认为left
     * $tag 标签名称，默认为空
     */
    public function set_attr($attribute,$tag=""){
        $this->sttrarr = $attribute;
        $this->tag = $tag;
        //获取一个函数是被哪个函数调用的
        $backtrace = debug_backtrace();
        array_shift($backtrace);
        $funcname = $backtrace[0]['function'];
        
        //设置元素属性
        $this->{'set_'.$funcname.'attr'}($attribute);
        return $this;
    }
    
}
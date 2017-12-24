<?php
include EDITMODELPATH."/parseattribute.php";
/**
 * 类使用示例
 * @author yx010
 *
    include "./edit/edit.class.php";                //引入编辑类
    $edit = new Edit;           
    $values = ['car'=>'轿车','bike'=>'自行车'];        //option选项内容。key为option 的 值 ，value为option 的选项名称
    $edit->select([                                 
    'values'=>$values,
    'value'=>'bike',                                //为select默认值 
    'etitle'=>'select标题',                                 
    'comment'=>'select注释',
    'position'=>'right',                            //select标题在select框的左边或右边
    'name'=>'myname',                               //select的name属性
    'class'=>'test',                                //类属性
    'disable'
    ]);
 */
class Select extends Parseattribute{
    /**
     * select属性解析 (将键值拼接为字串)函数名结构：set_标签名attr
     * @param array $attribute
     */
    public function set_selectattr($attribute){
        //定义非元素属性 (position:为标题所在的位置left|right。不设置则不输出标题和注释)
        $this->isnotattr = ['etitle','position','comment','values'];
        $attr = "";
        foreach($attribute as $key => $value){
            if(in_array($key, $this->isnotattr,true)) continue;
            $key = trim($key);
            $value = is_string($value) ? trim($value) : $value;
            $attr .= ($key == '0' || $key > 0) ? ' '.$value.' ' : ' ' . $key.'="'.$value.'"';
        }
        $this->attribute = $attr;
    }
    
    //输出input元素
    public function element(){
        $element = '<font class=""></font><select '.$this->attribute.'/><font class=""></font>';
        if(@$this->sttrarr['position'] == 'right'){
            $element =  '<font class="comment">'.@$this->sttrarr['comment'].'</font>'.'<select '.$this->attribute.'>'.$this->set_option().'</select>'.'<font class="etitle">'.@$this->sttrarr['etitle'].'</font>';
        }
        if(@$this->sttrarr['position'] == 'left'){
            $element = '<font class="etitle">'.@$this->sttrarr['etitle'].'</font>'.'<select '.$this->attribute.'>'.$this->set_option().'</select>'.'<font class="comment">'.@$this->sttrarr['comment'].'</font>';
        }
        echo  $element;
    }
    
    //option解析
    public function set_option(){
        $optionvalues = $this->sttrarr['values'];
        $curvalue = $this->sttrarr['value'];
        $option = "";
        foreach($optionvalues as $key => $value){
            $selected = ($key == $curvalue) ? ' selected ' : '';
            $option .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
        }
        return $option;
    }
    
    
    
}
<?php
include EDITMODELPATH."/parseattribute.php";
/**
 * 使用示例：
 * @author yx010
 *
 include "./edit/edit.class.php";
 $edit = new Edit;
 $edit->input([
    'etitle'=>'标题',
    'comment'=>'注释',
    'value'=>'默认值',
    'position'=>'left',
    'type'=>'text',
    'name'=>'myname',
    'class'=>'test',        //类名
    'disabled'
]);
 */
class Input extends Parseattribute{
    /**
     * input属性解析 (将键值拼接为字串)
     * @param array $attribute
     */
    public function set_inputattr($attribute){
        //定义非元素属性 (position:为标题所在的位置left|right。不设置则不输出标题和注释)
        $this->isnotattr = ['etitle','position','comment'];
        
        $attr = "";
        foreach($attribute as $key => $value){
            if(in_array($key, $this->isnotattr,true)) continue;
            //var_dump($key);echo "<br/>";
            $key = trim($key);
            $value = trim($value);
            $attr .= ($key == '0' || $key > 0) ? ' '.$value.' ' : ' ' . $key.'="'.$value.'"';
        }
        $this->attribute = $attr;
    }
    
    //输出input元素
    public function element(){
        $element = '<font class=""></font><input '.$this->attribute.'/><font class=""></font>';
        if(@$this->sttrarr['position'] == 'right'){
            $element =  '<font class="comment">'.@$this->sttrarr['comment'].'</font>'.'<input '.$this->attribute.'/>'.'<font class="etitle">'.@$this->sttrarr['etitle'].'</font>';
        }
        if(@$this->sttrarr['position'] == 'left'){
            $element = '<font class="etitle">'.@$this->sttrarr['etitle'].'</font>'.'<input '.$this->attribute.'/>'.'<font class="comment">'.@$this->sttrarr['comment'].'</font>';
        }
        echo  $element;
    }
}
<?php
include EDITMODELPATH."/parseattribute.php";
/**
 * 类使用示例
 * @author yx010
 *
    include "./edit/edit.class.php";                //引入编辑类
    $edit = new Edit;           
    $edit->select([                                 
    'value'=>'bike',                                //为select默认值 
    'etitle'=>'select标题',                                 
    'comment'=>'select注释',
    'position'=>'right',                            //select标题在select框的左边或右边
    'name'=>'myname',                               //select的name属性
    'class'=>'test',                                //类属性
    'disable'
    ]);
 */
class View extends Parseattribute{
    /**
     * Textarea属性解析 (将键值拼接为字串)函数名结构：set_标签名attr
     * @param array $attribute
     */
    public function set_viewattr($attribute){
        //定义非元素属性 (position:为标题所在的位置left|right。不设置则不输出标题和注释)
        $this->isnotattr = ['etitle','position','comment','value'];
        $attr = "";
        foreach($attribute as $key => $value){
            if(in_array($key, $this->isnotattr,true)) continue;
            $key = trim($key);
            $value = is_string($value) ? trim($value) : $value;
            $attr .= ($key == '0' || $key > 0) ? ' '.$value.' ' : ' ' . $key.'="'.$value.'"';
        }
        $this->attribute = $attr;
    }
    
    //输出view元素(如：div font ul li 等)
    public function element(){
        //var_dump($this->tag) ;exit;
        $tag = ($this->tag === "") ? 'view' : $this->tag;
        $element = '<'.$tag.' '.$this->attribute.'>'.@$this->sttrarr['value'].'</'.$tag.'>';
        if(@$this->sttrarr['position'] == 'right'){
            $element =  '<font class="comment">'.@$this->sttrarr['comment'].'</font>'.'<'.$tag.' '.$this->attribute.'>'.@$this->sttrarr['value'].'</'.$tag.'>'.'<font class="etitle">'.@$this->sttrarr['etitle'].'</font>';
        }
        if(@$this->sttrarr['position'] == 'left'){
            $element = '<font class="etitle">'.@$this->sttrarr['etitle'].'</font>'.'<'.$tag.' '.$this->attribute.'>'.@$this->sttrarr['value'].'</'.$tag.'>'.'<font class="comment">'.@$this->sttrarr['comment'].'</font>';
        }
        echo  $element;
    }
    
}
<?php 
namespace app\components;

use yii\base\Widget;

class Header extends Widget{
    public $datId = 5566;
    public function init(){
        parent::init();
    }
    public function run(){
        parent::run();
    }
    public function normal(){
        return $this->render("header/normal");
    }
}





?>
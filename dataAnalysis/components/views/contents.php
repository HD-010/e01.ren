<?php
namespace app\components;

use yii\base\Widget;

class Header extends Widget{
    public function init(){
        parent::init();
    }
    public function run(){
        parent::run();
    }
    public function eventOpt(){
        return $this->render("content/eventOpt");
    }
    public function leftNav(){
        return $this->render("content/leftNav");
    }
}
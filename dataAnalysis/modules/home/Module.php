<?php

namespace app\modules\home;

/**
 * @author 弘德誉曦
 *
 * 1、当前模块中的操作结果将会随视图返回，但不应用布局
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\home\controllers';
    public $defaultRoute = 'index';

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}

<?php

namespace app\modules\openapi;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\openapi\controllers';
    public $defaultRoute = 'index';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}

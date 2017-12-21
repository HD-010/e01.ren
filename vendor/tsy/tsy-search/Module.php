<?php

namespace tsy\search;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module  implements BootstrapInterface
{
    public $controllerNamespace = 'tsy\search\controller';
    public $defaultRoute = "game/index";

     /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['game'] = [
                'class' => 'tsy\search\console\GameController',
            ];
        }
    }
}

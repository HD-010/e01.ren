<?php
namespace app\modules\analysis\controllers;

use Yii;
use yii\base\Controller;
use app\modules\analysis\models\Schema;

class IndexController extends Controller
{
    public function actionIndex(){
        $schema = new Schema();
        $schema->setTableDesc('user');
        echo "<pre>";print_r(Schema::$userDesc);echo "</pre>";
    }
}


<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Version */

$this->title = Yii::t('app', '添加文章', [
    'modelClass' => 'Articles',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 文章列表), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-create">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

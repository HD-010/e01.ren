<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Articles */

$this->title = Yii::t('app', '修改文章: '.$model->id, [
        'modelClass' => 'Articles',
    ]) ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '文章列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', '修改文章:'.$model->id);
?>
<div class="articles-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>



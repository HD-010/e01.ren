<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\LionEditor;

$this->registerJsFile("/js/Framework.js");
$this->registerJsFile("/js/My97DatePicker/WdatePicker.js");
$this->registerJsFile("/js/jquery/tokeninput/jquery_tokeninput.js");
$this->registerCssFile("/js/jquery/tokeninput/token-input.css");
$this->registerCssFile("/js/jquery/tokeninput/token-input-facebook.css");
//加载弹出确认提示框的css文件
$this->registerCssFile("/css/sweet-alert/sweet-alert.css");

/* @var $this yii\web\View */
/* @var $model app\models\Version */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    window._tagjson = <?= isset($gametagjson)?$gametagjson:"[]" ?>;
</script>

<div class="version-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'pic_thumb')->fileInput() ?>

    <?= $form->field($model, 'updatetime')->textInput(['readonly'=>'true']) ?>


    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'context')->widget(LionEditor::className(),[
        'height' => '350px',
        'options' => array (
            'class' => 'form-control',
        )]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>


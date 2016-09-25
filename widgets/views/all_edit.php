<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use greeschenko\file\models\Attachments;

/* @var $this yii\web\View */
/* @var $model common\models\Files */
/* @var $form yii\widgets\ActiveForm */

$model = new Attachments;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        'id' => 'fileeditform'
    ],
]) ?>

<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
<?= $form->field($model, 'title')->textInput() ?>
<?= $form->field($model, 'description')->textArea() ?>

<div class="form-group text-right">
    <?= Html::a(Yii::t('file', 'Save'),'#', [
        'class' => 'btn btn-primary',
        'id' => 'fileeditform_submit'
    ]) ?>
</div>

<?php ActiveForm::end(); ?>

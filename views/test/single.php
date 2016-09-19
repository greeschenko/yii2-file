<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$model = new greeschenko\file\models\UploadModel;
?>
<div class="singleupload-form">
    <?php $form = ActiveForm::begin([
        'id' => 'singleupload-form',
        'action' => '/file/upload?type=all',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

    <?= $form->field($model, 'filedata')->fileInput() ?>

    <?= Html::submitButton('Save', ['class' =>'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>
</div>

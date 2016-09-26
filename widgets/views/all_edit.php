<?php
use yii\helpers\Html;
?>

<?=Html::hiddenInput('ho_file_info_edit_id','')?>

<div class="form-group">
    <?=Html::label(Yii::t('file', 'Title'),'ho_file_info_edit_id')?>
    <?=Html::textInput('ho_file_info_edit_title','',['class' => 'form-control'])?>
</div>

<div class="form-group">
    <?=Html::label(Yii::t('file', 'Description'),'ho_file_info_edit_description')?>
    <?=Html::textarea('ho_file_info_edit_description','',['class' => 'form-control'])?>
</div>

<div class="form-group text-right">
    <?= Html::a(Yii::t('file', 'Save'),'#', [
        'class' => 'btn btn-primary',
        'id' => 'fileeditform_submit'
    ]) ?>
</div>


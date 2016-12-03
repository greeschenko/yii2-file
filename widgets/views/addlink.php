<?php
use yii\helpers\Html;
?>

<?=Html::hiddenInput('ho_link_edit_id','')?>
<?=Html::hiddenInput('ho_link_edit_group',$gcode)?>
<?=Html::hiddenInput('ho_link_edit_type',$type)?>

<div class="form-group">
    <div class="row">
        <div class="col-md-9">
            <?=Html::textInput(
                'ho_link',
                '',
                [
                    'id' => 'input_link',
                    'class' => 'form-control',
                    'placeholder' => 'http://example.com'
                ]
            )?>
            <div class="help-block hidden"><?=Yii::t('file', 'Incorrect url format')?></div>
        </div>
        <div class="col-md-3">
            <?= Html::a(Yii::t('file', 'Save'),'#', [
                'class' => 'linkadd_submit btn btn-primary btn-block',
            ]) ?>
        </div>
    </div>
</div>


<?php if ($preset['addfile']): ?>
    <div class="ho_upload_limits"><?=$limits?></div>
    <div class="ho_upload_btn fileinput-button">
       <i class="fa fa-plus" aria-hidden="true"></i>
       <span><?= Yii::t('file', 'Select files...') ?></span>
        <?= $filefield ?>
    </div>
<?php else: ?>
    <div class="ho_upload_btn fileinput-button hidden">
        <?= $filefield ?>
    </div>
<?php endif; ?>

<?php if ($preset['addfile'] and $preset['addlink']): ?>
    <div class="ho_upload_or">
        <?= Yii::t('file', 'or') ?>
    </div>
<?php endif; ?>

<?php if ($preset['addlink']): ?>
    <div class="ho_upload_link_btn">
       <i class="fa fa-link" aria-hidden="true"></i>
       <span><?= Yii::t('file', 'Add link') ?></span>
    </div>
<?php endif; ?>
<div class="clearfix"></div>
<div class="ho_upload_res"></div>
<div class="clearfix"></div>


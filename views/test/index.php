<?php

use greeschenko\file\widgets\Upload;

?>

<h1>File Module Test Page</h1>

<?= Upload::widget([
    'groupcode' => 'test111'
]);?>

<?= Upload::widget([
    'groupcode' => 'test222'
]);?>

<?= Upload::widget([
    'groupcode' => 'test222'
]);?>

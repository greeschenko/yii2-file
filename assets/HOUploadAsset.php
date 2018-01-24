<?php

namespace greeschenko\file\assets;

use yii\web\AssetBundle;

class HOUploadAsset extends AssetBundle
{
    public $sourcePath = '@greeschenko/file/web';
    public $css = [
        'css/ho_upload.css?v=0.0.1',
    ];
    public $js = [
        'js/ho_upload.js?v=0.0.1',
        'js/ho_upload_init.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'greeschenko\file\assets\FontsAsset',
    ];
}

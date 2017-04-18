<?php

namespace greeschenko\file\assets;

use yii\web\AssetBundle;

class FilesGalleryAsset extends AssetBundle
{
    public $sourcePath = '@greeschenko/file/web';
    public $css = [
        'css/filesgallery.css',
    ];
    public $js = [
        'js/filesgallery.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'greeschenko\file\assets\FontsAsset',
    ];
}

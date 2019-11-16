<?php

namespace greeschenko\file\assets;

use yii\web\AssetBundle;

class FilesGalleryAsset extends AssetBundle
{
    public $sourcePath = '@greeschenko/file/web';
    public $css = [
        'css/filesgallery.css?v=0.0.1',
    ];
    public $js = [
        'js/filesgallery.js?v=0.0.2',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'greeschenko\file\assets\FontsAsset',
    ];
}

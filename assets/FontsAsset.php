<?php

namespace greeschenko\file\assets;

use yii\web\AssetBundle;

class FontsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];
}

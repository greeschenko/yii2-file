<?php

namespace greeschenko\file;

use Yii;

class Module extends \yii\base\Module
{
    const VER = '0.1-dev';

    public $i18n = [];
    public $presets = [];

    public function init()
    {
        parent::init();
        $this->initI18N();
    }

    public function initI18N()
    {
        Yii::setAlias('@file', dirname(__FILE__));
        if (empty($this->i18n)) {
            $this->i18n = [
                'sourceLanguage' => 'en',
                'basePath' => '@file/messages',
                'class' => 'yii\i18n\PhpMessageSource',
            ];
        }
        Yii::$app->i18n->translations['file'] = $this->i18n;
    }
}

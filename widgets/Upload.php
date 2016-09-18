<?php

namespace greeschenko\file\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use greeschenko\file\assets\UploadAsset;

class Upload extends Widget
{
    public $id;
    public $groupcode;
    public $type = 'all'; //one, img
    public $preset;
    public $module;
    public $options = [];
    public $clientOptions = [];

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
        $this->preset = $this->module->presets[$this->type];

        if (empty($this->groupcode)) {
            throw new InvalidConfigException(Yii::t('file','"groupcode" cannot be empty.'));
        }
    }

    public function run()
    {
        $filefield = Html::activeFileInput(
            new $this->preset['model'],
            $this->preset['attribute'],
            $this->genOptions()
        );

        echo $this->render($this->preset['view'], [
            'filefield' => $filefield,
            'preset' => $this->preset
        ]);

        $this->registerClientScript();
    }

    public function genOptions()
    {
        $res = array_merge(
            $this->preset['options'],
            $this->options
        );

        $res['id'] = ($this->id != null) ? $this->id : $this->getId();

        $res['data-clientOptions'] = array_merge(
            $this->preset['clientOptions'],
            $this->clientOptions
        );

        $res['data-groupcode'] = $this->groupcode;
        $res['data-url'] = $this->preset['clientOptions']['url'];

        return $res;
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        UploadAsset::register($view);

        $assetfile = $this->preset['assetsfile'];
        $assetfile::register($view);
    }
}

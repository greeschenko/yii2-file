<?php

namespace greeschenko\file\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use greeschenko\file\assets\UploadAsset;
use yii\bootstrap\Modal;

class Upload extends Widget
{
    public $id;
    public $groupcode;
    public $type = 'all'; //one, img
    public $preset;
    public $module;
    public $options = [];
    public $clientEvents = [];

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
        $limits = '';
        $filefield = Html::activeFileInput(
            new $this->preset['model'],
            $this->preset['attribute'],
            $this->genOptions()
        );

        $maxfilesize = $this->preset['rules']['maxSize']/(1000*1000).'Mb';

        if (isset($this->preset['rules']['extensions'])) {
            $limits = Yii::t('file', 'Allowed file extensions')
                .': '
                .$this->preset['rules']['extensions'];
        }

        $limits .= Yii::t('file', 'Maximum file size')
            .': '
            .$maxfilesize;

        echo Html::beginTag('div',['class' => 'ho_upload']);
        echo $this->render($this->preset['view'], [
            'filefield' => $filefield,
            'limits' => $limits,
            'preset' => $this->preset
        ]);
        echo Html::tag(
            'div',
            $this->render($this->preset['item_tmpl']),
            ['class' => 'hidden ho_upload_tmpl']);
        echo Html::tag('div','',['class' => 'ho_upload_errors']);
        echo Html::tag('div',Yii::t('file', 'Error loading file, check that the file meets'),['class' => 'ho_upload_fatalerrors']);

        Modal::begin([
            'options' => [ 'class' => 'edit-modal fade' ],
            'header' => '<h2>'.Yii::t('file', 'File Info').'</h2>',
        ]);
        echo $this->render($this->preset['edit']);
        Modal::end();

        Modal::begin([
            'options' => [ 'class' => 'link-modal fade' ],
            'header' => '<h2>'.Yii::t('file', 'Add link').'</h2>',
        ]);
        echo $this->render('addlink',[
            'gcode' => $this->groupcode,
            'type' => $this->type,
        ]);
        Modal::end();

        Modal::begin([
            'options' => [ 'class' => 'view-modal fade' ],
            'size' => "modal-lg",
        ]);
        Modal::end();

        echo Html::endTag('div');

        $this->registerClientScript();
    }

    public function genOptions()
    {
        $res = array_merge(
            $this->preset['options'],
            $this->options
        );

        $res['id'] = ($this->id != null) ? $this->id : $this->getId();

        $res['data-groupcode'] = $this->groupcode;
        $res['data-url'] = $this->preset['url'].'?type='.$this->type;

        return $res;
    }

    public function registerClientScript()
    {
        $js = [];
        $view = $this->getView();

        UploadAsset::register($view);

        $assetfile = $this->preset['assetsfile'];
        $assetfile::register($view);

        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$this->id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}

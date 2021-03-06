<?php

namespace greeschenko\file\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use greeschenko\file\assets\FilesGalleryAsset;
use greeschenko\file\models\Attachments;

class FilesGallery extends Widget
{
    public $id;
    public $groupcode;
    public $type = 'all'; //one, img
    public $preset;
    public $module;
    public $data;
    public $options = [];

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
        $this->preset = $this->module->presets[$this->type];
    }

    public function run()
    {
        echo Html::beginTag('div', ['class' => 'fg_wrap']);
        if ($this->groupcode != '') {
            $this->renderGroup();
        } else {
            $this->renderData();
        }
        echo Html::tag(
            'div',
            Html::tag('div', '', ['class' => 'fg_modal_title'])
            .Html::tag('div', '', ['class' => 'fg_modal_description'])
            .Html::tag('div', '', ['class' => 'fg_modal_content'])
            .Html::tag('div', '<i class="fa fa-download" aria-hidden="true"></i>', ['class' => 'fg_modal_download'])
            .Html::tag('div', '<i class="fa fa-times-circle" aria-hidden="true"></i>', ['class' => 'fg_modal_close']),
            ['class' => 'fg_modal']);
        echo Html::tag('div', '', ['class' => 'fg_blackwrap']);
        echo Html::endTag('div');
        echo Html::tag('div', '', ['class' => 'clearfix']);
        $this->registerClientScript();
    }

    /**
     * render files from group.
     */
    public function renderGroup()
    {
        $data = Attachments::find()
            ->where(['group' => $this->groupcode])
            ->all();
        if (count($data) > 0) {
            foreach ($data as $one) {
                echo $this->renderOne($one->file->getData(), $one->hash, $one->bind);
            }
        }
    }

    /**
     * render files from data.
     */
    public function renderData()
    {
        if (!empty($this->data)) {
            foreach ($this->data as $one) {
                echo $this->renderOne($one);
            }
        }
    }

    /**
     * render one file.
     */
    public function renderOne($data, $hash='', $bind='')
    {
        $res = '';
        $cl = 'fg_item_content';
        if (isset($data['old']) and $data['old']) {
            $cl .= ' oldfile';
        }
        $opt = ['class' => $cl];
        if (isset($data['icon'])) {
            $res .= Html::tag('div', $data['icon'], ['class' => 'icon']);
            $opt['data-src'] = $data['url'];
            $opt['data-type'] = 'doc';
            if ($data['type'] == 3
                or (isset($this->preset['no_doc_preview'])
                    and $this->preset['no_doc_preview'])
            ) {
                $opt['data-link'] = 1;
            }
        } else {
            //if (!isset($data['tumb']) and !isset($data['big'])) {
                //print_r($this->data);
                //die;
            //}
            $tumb = (isset($data['tumb'])) ? $data['tumb'] : $data['big'];
            $res .= Html::tag('div', '', [
                'class' => 'img',
                'style' => 'background-image:url("'.$tumb.'");',
            ]);
            $opt['data-src'] = $data['big'];
            $opt['data-type'] = 'img';
        }

        if (isset($data['name'])) {
            $name = $data['name'];
            if (isset($data['description']) and $data['description'] != '') {
                $name .= Html::tag(
                    'span',
                    ' ('.$data['description'].')',
                    ['class'=>'description']
                );
            }
            $res .= Html::tag(
                'div',
                $name,
                ['class' => 'name']
            );
            $opt['data-name'] = $data['name'];
        }

        if (isset($data['description'])) {
            $opt['data-description'] = $data['description'];
        }

        $res .= Html::tag(
            'div',
            '<i class="fa fa-info" aria-hidden="true"></i>',
            [
                'class' => 'fg_description',
                'onclick' => "
                    var el = $(this).parent();
                    el.find('.fg_info').fadeToggle();
                    return false;
                ",
            ]
        );

        $opt['data-hash'] = $hash;
        $opt['data-bind'] = $bind;

        $res .= Html::tag(
            'div',
            '<i class="fa fa-download" aria-hidden="true"></i>',
            [
                'class' => 'fg_direct_download',
                'onclick' => "
                    var el = $(this).parent();
                    window.open(el.data('src'));
                    return false;
                ",
            ]
        );

        if (isset($data['size'])) {
            $res .= Html::tag(
                'div',
                round(((int)$data['size'] / 1000000), 2).'Mb',
                ['class' => 'size']
            );
        }

        $txt = "";
        if (isset($data['name']) and $data['name'] != '') {
            $txt .= $data['name']."</br>";
        }
        if (isset($data['description']) and $data['description'] != '') {
            $txt .= $data['description'];
        }

        $res .= Html::tag(
            'div',
            $txt,
            ['class' => 'fg_info']
        );

        echo Html::tag(
            'div',
            Html::tag('div', $res, $opt),
            ['class' => 'fg_item']);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        FilesGalleryAsset::register($view);
    }
}

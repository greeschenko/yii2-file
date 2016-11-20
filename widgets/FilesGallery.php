<?php

namespace greeschenko\file\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use greeschenko\file\assets\FilesGalleryAsset;
use greeschenko\file\models\Attachments;

class FilesGallery extends Widget
{
    public $id;
    public $groupcode;
    public $data;
    public $options = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        echo Html::beginTag('div',['class' => 'fg_wrap']);
        if ($this->groupcode != '') {
            $this->renderGroup();
        } else {
            $this->renderData();
        }
        echo Html::tag(
            'div',
            Html::tag('div','',['class' => 'fg_modal_title'])
            .Html::tag('div','',['class' => 'fg_modal_description'])
            .Html::tag('div','',['class' => 'fg_modal_content'])
            .Html::tag('div','<i class="fa fa-download" aria-hidden="true"></i>',['class' => 'fg_modal_download'])
            .Html::tag('div','<i class="fa fa-times-circle" aria-hidden="true"></i>',['class' => 'fg_modal_close']),
            ['class' => 'fg_modal']);
        echo Html::tag( 'div', '', ['class' => 'fg_blackwrap']);
        echo Html::endTag('div');
        echo Html::tag('div','',['class' => 'clearfix']);
        $this->registerClientScript();
    }

    /**
     * render files from group
     *
     * @return void
     */
    public function renderGroup()
    {
        $data = Attachments::find()
            ->where(['group' => $this->groupcode])
            ->all();
        if (count($data) > 0) {
            foreach ($data as $one) {
                echo $this->renderOne($one->file->getData());
            }
        }
    }

    /**
     * render files from data
     *
     * @return void
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
     * render one file
     *
     * @return void
     */
    public function renderOne($data)
    {
        $res = '';
        $opt = ['class' => 'fg_item_content'];
        if (isset($data['icon'])) {
            $res .= Html::tag('div',$data['icon'],['class' => 'icon']);
            $opt['data-src'] = $data['url'];
            $opt['data-type'] = 'doc';
            if ($data['type'] == 3) {
                $opt['data-link'] = 1;
            }
        } else {
            $tumb = (isset($data['tumb'])) ? $data['tumb'] : $data['big'];
            $res .= Html::tag('div','',[
                'class' => 'img',
                'style'=>'background-image:url("'.$tumb.'");',
            ]);
            $opt['data-src'] = $data['big'];
            $opt['data-type'] = 'img';
        }

        if (isset($data['name'])) {
            $res .= Html::tag(
                'div',
                $data['name'],
                ['class'=>'name']
            );
            $opt['data-name'] = $data['name'];
        }

        $res .= Html::tag(
            'div',
            '<i class="fa fa-download" aria-hidden="true"></i>',
            ['class' => 'fg_download']
        );

        if (isset($data['description'])) {
            /*$res .= Html::tag(
                'div',
                $data['description'],
                ['class'=>'description']
            );*/
            $opt['data-description'] = $data['description'];
        }
        if (isset($data['size'])) {
            $res .= Html::tag(
                'div',
                round(($data['size']/1000000),2).'Mb',
                ['class'=>'size']
            );
        }
        echo Html::tag(
            'div',
            Html::tag('div',$res,$opt),
            ['class' => 'fg_item col-md-3 col-lg-2 col-sm-4']);
    }

    public function registerClientScript()
    {
        $view = $this->getView();
        FilesGalleryAsset::register($view);
    }
}

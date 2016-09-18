<?php

namespace greeschenko\file\controllers;

use Yii;
use yii\web\Controller;

class UploadController extends Controller
{

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
    }

    public function actionIndex($type)
    {
        $preset = $this->module->presets[$type];

        $model = new $preset['model'];

        if (Yii::$app->request->isPost) {
            $model->filedata = \yii\web\UploadedFile::getInstances(
                $model,
                $preset['attribute']
            );

            if ( $data = $model->upload() ) {
                echo $data;
                exit;
            }
        }
    }
}

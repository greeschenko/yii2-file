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

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new $preset['model'];
        $model->preset = $type;

        if (Yii::$app->request->isPost) {
            $model->filedata = \yii\web\UploadedFile::getInstances(
                $model,
                $preset['attribute']
            );

            if ( $data = $model->upload() ) {
                return $data;
            } else {
                return $model->errors;
            }
        }
    }
}

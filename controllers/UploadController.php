<?php

namespace greeschenko\file\controllers;

use Yii;
use yii\web\Controller;
use greeschenko\file\models\Files;
use greeschenko\file\models\Attachments;

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

    public function actionLink()
    {
        $res = [];
        $model = new Files;
        $attach = new Attachments;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if ($data['id'] == '') {
                $model->name = $data['link'];
                $model->path = $data['link'];
                $model->ext = 'link';
                $model->preset = $data['type'];
                $model->type = Files::TYPE_LINK;

                if ($model->save()) {
                    $attach->group = $data['group'];
                    $attach->file_id = $model->id;
                    if ($attach->save()) {
                        $res['result'] = 'success';
                    } else {
                        $res['result'] = 'error';
                        $res['msg'] = $attach->errors;
                    }
                } else {
                    $res['result'] = 'error';
                    $res['msg'] = $model->errors;
                }
            }
        }

        return $res;
    }
}

<?php

namespace greeschenko\file\controllers;

use Yii;
use yii\web\Controller;
use greeschenko\file\models\Attachments;

class DoController extends Controller
{

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('file');
    }

    public function actionAttach($file_id,$gcode)
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isGet and Yii::$app->request->isAjax) {
            $model = new Attachments;
            $model->group = $gcode;
            $model->file_id = $file_id;
            if ($model->save()) {
                $res['result'] = 'success';
            } else {
                $res['result'] = 'error';
                $res['msg'] = $model->errors;
            }
        }

        return $res;
    }

    public function actionGetGroupList($gcode)
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isGet and Yii::$app->request->isAjax) {
            $data = Attachments::find()->where(['group' => $gcode])->all();
            foreach ($data as $i=>$one) {
                $res[$i] = $one->file->getData();
                if ($one->title != '') {
                    $res[$i]['name'] = $one->title;
                }
                $res[$i]['description'] = $one->description;
            }
        }

        return $res;
    }
}

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

    public function actionAttach($file_id, $gcode, $replace)
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isGet and Yii::$app->request->isAjax) {
            if ($replace == 0) {
                $model = new Attachments();
            } else {
                $model = Attachments::findOne($replace);
            }
            if ($model != null) {
                $model->group = $gcode;
                $model->file_id = $file_id;
                $model->index = Attachments::find()->where(['group' => $gcode])->count();
                if ($model->save()) {
                    $res['result'] = 'success';
                } else {
                    $res['result'] = 'error';
                    $res['msg'] = $model->errors;
                }
            } else {
                $res['result'] = 'error';
                $res['msg'] = Yii::t('file', 'File attach filed');
            }
        }

        return $res;
    }

    public function actionUnattach()
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = Attachments::findOne($data['id']);
            if ($model != null) {
                if ($model->delete()) {
                    $res['result'] = 'success';
                } else {
                    $res['result'] = 'error';
                    $res['msg'] = $model->errors;
                }
            } else {
                $res['result'] = 'error';
                $res['msg'] = 'model not found';
            }
        }

        return $res;
    }

    public function actionGetGroupList($gcode)
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isGet and Yii::$app->request->isAjax) {
            $data = Attachments::find()
                ->where(['group' => $gcode])
                ->orderBy('index')
                ->all();
            foreach ($data as $i => $one) {
                $res[$i] = $one->file->getData();
                if ($one->title != '') {
                    $res[$i]['name'] = $one->title;
                }
                $res[$i]['description'] = $one->description;
                $res[$i]['attach_id'] = $one->id;
            }
        }

        return $res;
    }

    public function actionChangeInfo()
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost and Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $model = Attachments::findOne($data['id']);
            if ($model != null) {
                $model->title = $data['title'];
                $model->description = $data['description'];
                if ($model->save()) {
                    $res['result'] = 'success';
                }
            } else {
                $res['result'] = 'error';
                $res['msg'] = $model->errors;
            }
        }

        return $res;
    }

    /**
     * fix hash data for all old files.
     */
    public function actionFixHash()
    {
        $data = Attachments::find()->all();
        foreach ($data as $one) {
            $md = '';
            $fdata = $one->file->getData();
            $url = (isset($fdata['big']))
                        ? $fdata['big']
                        : $fdata['url'];
            $url = realpath('.').$url;
            if (is_file($url)) {
                $md = 'md5:'.md5_file($url);
                $one->hash = $md;
                if ($one->save()) {
                    echo $one->id.'...OK';
                } else {
                    echo $one->id.'...FAIL';
                }
                echo '<br>';
            }
        }
    }

    public function actionSyncOrder($list)
    {
        $res = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {
            foreach (json_decode($list) as $key => $value) {
                $model = Attachments::findOne($value);
                if ($model != null) {
                    $model->index = $key;
                    if ($model->save()) {
                        $res['result'] = 'success';
                    } else {
                        $res['result'] = 'error';
                        $res['msg'] = $model->errors;
                        break;
                    }
                }
            }
        }

        return $res;
    }

    public function actionRead($path, $name)
    {
        $file = 'uploads/'.$path.'/'.$name;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }else{
            echo "<pre>";
            print_r(['file not exist',$file]);
            echo "</pre>";
            die;
        }
    }
}

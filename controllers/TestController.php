<?php

namespace greeschenko\file\controllers;

use yii\web\Controller;

/**
 * login controller for the `user` module
 */
class TestController extends Controller
{

    /**
     * test main action.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * test single upload action.
     *
     * @return string
     */
    public function actionSingle()
    {
        return $this->render('single');
    }
}

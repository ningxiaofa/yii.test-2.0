<?php

namespace app\controllers;

use yii\base\Controller;

class BlogController extends Controller
{
    public function actionView()
    {
        echo "this is actionView";
    }

    public function actionUpdate()
    {
        echo "this is actionUpdate";
    }

    public function actionDelete()
    {
        echo "this is actionDelete";
    }
}
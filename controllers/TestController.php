<?php

namespace app\controllers;

use yii\web\Controller;

class TestController extends Controller
{

   
    /**
     * Just test beauty url
     *
     * @return string
     */
    public function actionView(int $id)
    {
        var_dump($id);
        exit;
    }

    // http://yii.test:8080/test/index?id=100
    // 
    public function actionIndex(string $id)
    {
        var_dump($id);
    }

}

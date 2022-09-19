<?php

namespace app\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class CarController extends Controller
{

    // access: http://yii.test:8080/ford/evos
    public function actionIndex($manufacturer, $model)
    {
        // var_dump($manufacturer, $model); // string(4) "ford" string(4) "evos" this is actionIndex
        // echo "this is actionIndex" . PHP_EOL;

        // /ford/hahaha
       $url1 =  Url::to(['car/index', 'manufacturer' => 'ford', 'model' => 'hahaha']); 
       $url2 = Url::to(['car/index', 'manufacturer' => 'ford', 'model' => 'hahaha'], true);
       
       $response = Yii::$app->response;
       $response->format = Response::FORMAT_JSON;
       $response->data = [$url1, $url2];
       return $response->send(); // ["/ford/hahaha","http://yii.test:8080/ford/hahaha"]
    }
}

<?php

namespace app\controllers;

use yii\web\Controller;

class CommentController extends Controller
{
    // http://yii.test:8080/comment/10/update
    public function actionUpdate(int $id)
    {
        // code here
        echo $id, 'This is actionUpdate';
    }

    // http://yii.test:8080/comment/10/delete
    public function actionDelete(int $id)
    {
        // code here
        echo $id, 'This is actionDelete';
    }

    // http://yii.test:8080/comments
    public function actionIndex()
    {
        echo 'This is actionIndex';
    }

}

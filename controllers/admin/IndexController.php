<?php

namespace app\controllers\admin;

use yii\web\Controller;

class IndexController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // http://yii.test:8080/index.php?r=admin/index/index
        echo __FILE__; // [/var/www/html/yii.test/controllers/admin/IndexController.php]
        return $this->render('index');
    }
}

<?php

namespace app\controllers\admin;

use yii\web\Controller;

class UserController extends Controller
{
    /**
     * @return string
     */
    public function actionLogin()
    {
        echo __FILE__ . 'action: login'; // [/var/www/html/yii.test/controllers/admin/UserController.php]
    }
}

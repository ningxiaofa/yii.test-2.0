<?php

namespace app\commands;

use yii\console\Controller;

class IndexController extends Controller
{

    public function actionIndex()
    {
        echo __FILE__;

        // ➜  yii.test git:(master) ✗ php72 yii index/index
        // /Users/huangbaoyin/Documents/Env/docker-lnmp-dev-env-sh/html/yii.test/commands/IndexController.php%                                
        // ➜  yii.test git:(master) ✗ 

    }

    //  php yii index/argv name=ningxiaofa age=30
    public function actionArgv($argv, $argv1)
    {
        var_dump($argv, $argv1);

        // 输出结果：
        // ➜  yii2.test git:(main) ✗ php yii index/argv name=ningxiaofa age=30
        // string(15) "name=ningxiaofa"
        // string(6) "age=30"
        // ➜  yii2.test git:(main) ✗ 
    }

    //  php yii index/list m=123 abc xyz
    public function actionList($argv, $argv1, $argv2)
    {
        var_dump($argv, $argv1, $argv2);
        // ➜  yii2.test git:(main) ✗ php yii index/list m=123 abc xyz
        // string(5) "m=123"
        // string(3) "abc"
        // string(3) "xyz"
        // ➜  yii2.test git:(main) ✗ 
    }
}

<?php

namespace app\controllers;

use yii\web\Controller;

class IndexController extends Controller
{

    public function beforeAction($action)
    {
        echo 'before';
        // var_dump($action); // object(yii\base\InlineAction) 超大对象
        // code here [通常用来做权限验证, 以及配合afterAction来计算 某个动作方法的执行时间长短]

        // your custom code here, if you want the code to run before action filters,
        // which are triggered on the [[EVENT_BEFORE_ACTION]] event, e.g. PageCache or AccessControl

        if (!parent::beforeAction($action)) {
            return false;
        }
            
        // other custom code here
        return true; // or false to not run the action，而且如果没有返回值，也会导致后面的代码不会执行
        
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        echo 'action';
        // return [123];
        return $this->render('test');
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        

        // your custom code here
        echo 'after';
        // var_dump($action); // object(yii\base\InlineAction) 超大对象
        // var_dump($result); // 就是对应的action方法中返回的结果，但是如果是view视图，会是空字符串, 接口响应是不变的，只不过，yii会使用视图的内容重新覆盖, 也就是看不到echo 'before';echo 'action';echo 'after';的输出内容在页面上.


        return $result; // 要将返回值保护返回出去，当然也可以统一做一下修改.
    }
}

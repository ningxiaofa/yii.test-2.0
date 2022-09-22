<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class SessionController extends Controller
{
    // 开启和关闭 Sessions ¶
    public function actionIndex()
    {
        // 
        $session = Yii::$app->session;

        // 检查session是否开启 
        if ($session->isActive) {
            echo 'Yep, it is';
        }

        // 开启session
        $session->open();

        // 关闭session
        $session->close();

        // 销毁session中所有已注册的数据
        $session->destroy();
    }

    // 访问 Session 数据
    public function actionInfo()
    {
        $session = Yii::$app->session;

        // 获取session中的变量值，以下用法是相同的：
        $language = $session->get('language');
        $language = $session['language'];
        $language = isset($_SESSION['language']) ? $_SESSION['language'] : null;
        if(!empty($language)){
            echo "Language exists, it is $language";
        }

        // 设置一个session变量，以下用法是相同的：
        $session->set('language', 'en-US');
        $session['language'] = 'en-US';
        $_SESSION['language'] = 'en-US';
        echo $session->get('language') . PHP_EOL;

        // 删除一个session变量，以下用法是相同的：
        $session->remove('language');
        unset($session['language']);
        unset($_SESSION['language']);
        echo $session->get('language') . PHP_EOL;

        // 检查session变量是否已存在，以下用法是相同的：
        if ($session->has('language')) {
            echo 'Yep, it has' . PHP_EOL;;
        }
        if (isset($session['language'])) {
            echo 'Yep, it is isset' . PHP_EOL;;
        }
        if (isset($_SESSION['language'])) {
            echo 'Yep, it is isset' . PHP_EOL;;
        }

        // 遍历所有session变量，以下用法是相同的：
        foreach ($session as $name => $value) {
            // string(7) "__flash" array(0) { } 
            var_dump($name, $value);
            echo PHP_EOL;
        }
        foreach ($_SESSION as $name => $value) {
            // string(7) "__flash" array(0) { } 
            var_dump($name, $value);
            echo PHP_EOL;
        }
    }

    // 当 session 数据为数组时，session 组件会限制你直接修改数据中的单元项， 例如：
    public function actionModify()
    {
        $session = Yii::$app->session;

        // 如下代码不会生效
        // 而且会报异常： -- 所以做下测试就好了, 
        // PHP Notice – yii\base\ErrorException
        // Indirect modification of overloaded element of yii\web\Session has no effect
        // 解决办法：见方法 -- actionModifyArray

        // $session['captcha']['number'] = 5;
        // $session['captcha']['lifetime'] = 3600;
        
        // 如下代码会生效：
        $session['captcha'] = [
            'number' => 5,
            'lifetime' => 3600,
        ];
        
        // 如下代码也会生效：
        echo $session['captcha']['lifetime'] . "<br/>" . PHP_EOL;

        var_export($session['captcha']);
    }

    // 数组限制修改数据中的单元项，解决办法
    // 可使用以下任意一个变通方法来解决这个问题：
    public function actionModifyArray()
    {
        $session = Yii::$app->session;
        // var_dump($session->id); // string(26) "uub0h09pfsgusu1sih0ps1644v"

        // var_dump($session);
        // exit;
        // object(yii\web\Session)#52 (9) {
        // ["flashParam"]=>
        // string(7) "__flash"
        // ["handler"]=>
        // NULL
        // ["_forceRegenerateId":protected]=>
        // NULL
        // ["_cookieParams":"yii\web\Session":private]=>
        // array(1) {
        //     ["httponly"]=>
        //     bool(true)
        // }
        // ["frozenSessionData":"yii\web\Session":private]=>
        // NULL
        // ["_hasSessionId":"yii\web\Session":private]=>
        // bool(true)
        // ["_events":"yii\base\Component":private]=>
        // array(0) {
        // }
        // ["_eventWildcards":"yii\base\Component":private]=>
        // array(0) {
        // }
        // ["_behaviors":"yii\base\Component":private]=>
        // NULL
        // }
       
        $session->destroy();
        // var_dump($session->id); // string(26) "uub0h09pfsgusu1sih0ps1644v"
        // var_dump($session);
        // exit;


        // 直接使用$_SESSION (确保Yii::$app->session->open() 已经调用)
        $_SESSION['captcha']['number'] = 5;
        $_SESSION['captcha']['lifetime'] = 3600;
        var_dump($_SESSION['captcha']);
        echo "<br/>";

        // 先获取session数据到一个数组，修改数组的值，然后保存数组到session中
        $captcha = $session['captcha'];
        $captcha['number'] = 5;
        $captcha['lifetime'] = 3600;
        $session['captcha'] = $captcha;
        var_dump($session['captcha']);
        echo "<br/>";

        // 使用ArrayObject 数组对象代替数组
        $session['captcha'] = new \ArrayObject;
        $session['captcha']['number'] = 5;
        $session['captcha']['lifetime'] = 3600;
        var_dump($session['captcha']);
        echo "<br/>";

        // 使用带通用前缀的键来存储数组 -- 本质上也是 使用ArrayObject 数组对象代替数组。
        $session['captcha.number'] = 5;
        $session['captcha.lifetime'] = 3600;
        // object(ArrayObject)#51 (1) { ["storage":"ArrayObject":private]=> array(2) { ["number"]=> int(5) ["lifetime"]=> int(3600) } }
        var_dump($session['captcha']);
        echo "<br/>";
    }

    // Flash 数据
    // // Flash 数据是一种特别的 session 数据，它一旦在某个请求中设置后， 只会在下次请求中有效，然后该数据就会自动被删除。 常用于实现只需显示给终端用户一次的信息， 如用户提交一个表单后显示确认信息。
    // 可通过 session 应用组件设置或访问 session，例如：
    public function actionFlash()
    {
        $session = Yii::$app->session;
        // var_dump($session);
        // exit;

        // 请求 #1
        // 设置一个名为"postDeleted" flash 信息
        $session->setFlash('postDeleted', 'You have successfully deleted your post.');
         var_dump($session);
        exit;
        // 请求 #2
        // 显示名为"postDeleted" flash 信息
        echo $session->getFlash('postDeleted');
        
        // 请求 #3
        // $result 为 false，因为flash信息已被自动删除
        $result = $session->hasFlash('postDeleted');
        var_dump($result);
        echo "<br/>";
        // 输出结果
        // 第一次请求：You have successfully deleted your post.bool(true)


        // 和普通 session 数据类似，可将任意数据存储为 flash 数据。
        // 当调用 yii\web\Session::setFlash() 时, 会自动覆盖相同名的已存在的任何数据， 为将数据追加到已存在的相同名 flash 中，可改为调用 yii\web\Session::addFlash()。
        // 参见actionAddFlash
    }

    // 添加Flash
    public function actionAddFlash()
    {
        $session = Yii::$app->session;

        // 请求 #1
        // 在名称为"alerts"的flash信息增加数据
        $session->addFlash('alerts', 'You have successfully deleted your post.');
        $session->addFlash('alerts', 'You have successfully added a new friend.');
        $session->addFlash('alerts', 'You are promoted.');

        // 请求 #2
        // $alerts 为名为'alerts'的flash信息，为数组格式
        $alerts = $session->getFlash('alerts');
    }
}

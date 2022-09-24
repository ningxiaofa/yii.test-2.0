<?php

namespace app\controllers;

use yii\web\Response;
use yii\base\ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

// https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-handling-errors
// error handler 错误处理器默认启用， 可通过在应用的入口脚本中定义常量YII_ENABLE_ERROR_HANDLER来禁用。
class ErrorHandlerController extends Controller
{
    // 使用错误处理器
    // error handler 注册成一个名称为errorHandler应用组件， 可以在应用配置中配置它类似如下：
    public function actionIndex()
    {
        try {
            10/0;
        } catch (ErrorException $e) {
            // $e->getMessage();
            // $e->getLine();
            
            // 写入runtime/app.log，不会返回给前端信息
            Yii::warning("Division by zero.");
            // 写入的内容：
            // 2022-09-24 10:17:26 [172.18.0.1][100][-][warning][application] Division by zero.
            // in /var/www/html/yii.test/controllers/ErrorHandlerController.php:23

            // app.log中还同时写入了$_GET，$_POST, $_COOKIE, $_SESSION, $_SERVER等超全局变量的信息
            // 这应该是yii框架默认写入的访问日志信息，应可以关闭，见LogController.php
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'code' => 1,
            'message' => 'just for test',
            'data' => [],
        ];
    }

    public function actionException()
    {
        throw new NotFoundHttpException();

        // 浏览器页面显示内容如下，注意并不是获取到的响应内容就是下面，因为有各种样式
        // Not Found (#404)
        // The above error occurred while the Web server was processing your request.
        // Please contact us if you think this is a server error. Thank you.
        // 2022-09-24 10:34:37
    }

    // 自定义错误显示
    // error handler错误处理器根据常量YII_DEBUG的值来调整错误显示， 当YII_DEBUG 为 true (表示在调试模式)， 错误处理器会显示异常以及详细的函数调用栈和源代码行数来帮助调试， 当YII_DEBUG 为 false，只有错误信息会被显示以防止应用的敏感信息泄漏。
    // 使用错误动作 ¶ -- 'errorAction' => 'site/error',
    // errorAction 属性使用 路由到一个操作， 上述配置表示不用显示函数调用栈信息的错误会通过执行site/error操作来显示。
    public function actions()
    {
        return [
            // error是actionError，针对某些action控制器动作进行的设置, 而且，这里可以设置error-handler/error为全局错误处理动作，如果使用yii\web\ErrorAction，这种方式，比如当前端请求错误的路由：index/index1, error-handler/error1, 
            // 都会显示yii已经定义好的错误视图内容【就是在当前控制器/动作对应的视图目录下的error.php文件内容】
            // 使用actionError的方式也是一样
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];

        // 输出信息：
        // YII_DEBUG 为 true时，环境仍为dev
        // 报错信息如下：
        // An Error occurred while handling another error:
        //     yii\base\InvalidArgumentException: Response content must not be an array. in /var/www/html/yii.test/vendor/yiisoft/yii2/web/Response.php:1111
        //     Stack trace:
        //     #0 /var/www/html/yii.test/vendor/yiisoft/yii2/web/Response.php(339): yii\web\Response->prepare()
        //     #1 /var/www/html/yii.test/vendor/yiisoft/yii2/web/ErrorHandler.php(136): yii\web\Response->send()
        //     #2 /var/www/html/yii.test/vendor/yiisoft/yii2/base/ErrorHandler.php(152): yii\web\ErrorHandler->renderException(Object(yii\base\InvalidArgumentException))
        //     #3 [internal function]: yii\base\ErrorHandler->handleException(Object(yii\base\InvalidArgumentException))
        //     #4 {main}
        //     Previous exception:
        //     yii\base\InvalidArgumentException: Response content must not be an array. in /var/www/html/yii.test/vendor/yiisoft/yii2/web/Response.php:1111
        //     Stack trace:
        //     #0 /var/www/html/yii.test/vendor/yiisoft/yii2/web/Response.php(339): yii\web\Response->prepare()
        //     #1 /var/www/html/yii.test/vendor/yiisoft/yii2/base/Application.php(390): yii\web\Response->send()
        //     #2 /var/www/html/yii.test/web/index.php(12): yii\base\Application->run()
        //     #3 {main}

        // YII_DEBUG 为 false时，环境仍为dev
        // 报错信息如下：
        // An internal server error occurred.

        // 如果环境改为prod，跟dev相同，
        // error handler 错误处理器默认使用两个视图显示错误，只跟YII_DEBUG的value有关
    }

    // 或者 ----------- 当两者都存在时，优先执行actions， 而且actions本质上就是过滤器
    // 如果actions方法中return，便不再执行相应的action方法动作

    public function actionError() // http://yii.test:8080/error-handler/error, 正常访问该路由，是没问题的，Yii::$app->errorHandler->exception的value也就为NULL
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }

        // mock vars
        return $this->render('error', [
            'name' => 'i am name',
            'message' => 'i am message',
            'exception' => 'i am exception',
        ]);
    }

    // 怎么触发呢？
    public function actionErr()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            10/0; // 会异常

            return [
                'code' => 1,
                'message' => 'success',
                'data' => [],
            ];

        } catch (ErrorException $e) {
            // var_dump($e->getMessage()); // string(16) "Division by zero"
            // var_dump($e->getLine()); //  int(117)
            
            // throw $e; // 捕获后，直接抛出，不处理的话，通常跟不捕获没什么区别，
            // 但是这里，会被yii的错误处理程序捕获，写入日志，不过内容, 包括详细的堆栈信息，过于多了，而且直接返回这些信息给前端，并不友好，
            // 在非debug模式下，甚至根本不知道发生了错误, 不利于快速排查解决问题.
            // 但，记住：异常要捕获，而且一定要处理【打日志，终止脚本，发送告警通知等】，不能直接接着抛出。


            // 可参考如下:
            // 1. 写入日志 -- 可以考虑只写入简洁的错误信息，但是可能对对于排查问题并不友好，对于错误，还是写全一些比较好。
            Yii::error($e); 
             
            // 2. 返回给前端 --- 应不必太详细，毕竟不能太暴露隐私安全
            return [
                'code' => 0,
                'message' => 'Err: '. $e->getMessage(),
            ];
        }
    }

}


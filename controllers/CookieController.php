<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

class CookieController extends Controller
{
    // 读取 Cookies ¶
    public function actionIndex()
    {
        // 从 "request" 组件中获取 cookie 集合(yii\web\CookieCollection)
        $cookies = Yii::$app->request->cookies;

        // 获取名为 "language" cookie 的值，如果不存在，返回默认值 "en"
        $language = $cookies->getValue('language', 'en');
        var_dump($language);

        // 另一种方式获取名为 "language" cookie 的值
        if (($cookie = $cookies->get('language')) !== null) {
            $language = $cookie->value;
        }

        // 可将 $cookies 当作数组使用
        if (isset($cookies['language'])) {
            $language = $cookies['language']->value;
        }

        // 判断是否存在名为 "language" 的 cookie
        if ($cookies->has('language')) {
            echo 'yep, it has';
        }
        if (isset($cookies['language'])) {
            echo 'yep, it has';
        }
    }

    // 发送 Cookies ¶
    public function actionSendCookie()
    {
        // 从 "response" 组件中获取 cookie 集合(yii\web\CookieCollection)
        $cookies = Yii::$app->response->cookies;

        // 在要发送的响应中添加一个新的 cookie
        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => 'zh-CN',
        ]));

        // // 删除一个 cookie
        // $cookies->remove('language');
        // // 等同于以下删除代码
        // unset($cookies['language']);
    }

    // Cookie 验证

    // 在上两节中，当通过 request 和 response 组件读取和发送 cookie 时， 你会喜欢扩展的 cookie 验证的保障安全功能，它能 使 cookie 不被客户端修改。该功能通过给每个 cookie 签发一个哈希字符串来告知服务端 cookie 是否在客户端被修改， 如果被修改，通过 request 组件的 cookie collection cookie 集合访问不到该 cookie。

    // Cookie 验证默认启用，可以设置 yii\web\Request::$enableCookieValidation 属性为 false 来禁用它， 尽管如此，我们强烈建议启用它。

    // 注意： 直接通过 $_COOKIE 和 setcookie() 读取和发送的 Cookie 不会被验证。
    public function actionValidate()
    {

    }
}
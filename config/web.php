<?php

// 很多的配置默认参数，参见 vendor/yiisoft/yii2/base/Application.php
// https://www.yiiframework.com/doc/api/2.0/yii-base-application#$controllerNamespace-detail

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// Added by self
$urlManager = require __DIR__ . '/url_manager.php';
$request = require __DIR__ . '/request.php';
$session = require __DIR__ . '/session.php';
$errorHandler = require __DIR__ . '/error_handler.php';
$response = require __DIR__ . '/response.php';
$log = require __DIR__ . '/log.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    // log 组件必须在 bootstrapping 期间就被加载，以便于它能够及时调度日志消息到目标里。 
    'bootstrap' => ['log',],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],

    // 设置合适的时区，因为yii应用使用到的时间日期函数，都是根据当前时区来生成的，通常在日日志中用到的比较多
    'timeZone' => 'PRC', // 中华人民共和国

    'components' => [
        'request' => $request,

        // 响应
        'response' => $response,

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        // 日志
        'log' => $log,

        'db' => $db,
        'session' => $session,

        // url美化 「https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-routing」
        'urlManager' => $urlManager,

        // 错误处理
        'errorHandler' => $errorHandler,
    ],
    'params' => $params,

    // 默认的控制器命令空间
    'controllerNamespace' => 'app\\controllers',

    // 强制控制器ID和类名对应， 通常用在使用第三方不能掌控类名的控制器上。
    // 但是视图文件还是会找到account目录的对应的视图文件, 应该也是可以改的，具体如何做，TBD
    'controllerMap' => [
        // 用类名申明 "account" 控制器
        'account' => 'app\controllers\IndexController',

        // 用配置数组申明 "article" 控制器
        'article' => [
            'class' => 'app\controllers\IndexController',
            'enableCsrfValidation' => false,
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.1'], // 172.18.0.1是容器中的IP地址，如果不添加上，runtime/app.log中会一直写入如下warning信息：'2022-09-24 10:26:04 [172.18.0.1][100][-][warning][yii\debug\Module::checkAccess] Access to debugger is denied due to IP address restriction. The requesting IP address is 172.18.0.1' --- gii亦同
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.18.0.1'], 
    ];
}

return $config;

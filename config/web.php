<?php

// 很多的配置默认参数，参见 vendor/yiisoft/yii2/base/Application.php
// https://www.yiiframework.com/doc/api/2.0/yii-base-application#$controllerNamespace-detail

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// Added by self
$urlManager = require __DIR__ . '/url_manager.php';
$request = require __DIR__ . '/request.php';
$session = require __DIR__ . '/session.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => $request,

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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'session' => $session,

        // url美化 「https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-routing」
        'urlManager' => $urlManager,
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
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

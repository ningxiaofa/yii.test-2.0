<?php

// 很多的配置默认参数，参见 vendor/yiisoft/yii2/base/Application.php
// https://www.yiiframework.com/doc/api/2.0/yii-base-application#$controllerNamespace-detail

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yNcsx_czEx5Hb1Oh93nzF34eUOiVlU5Q',
        ],
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

        // url美化
        'urlManager' => [
            'enablePrettyUrl' => true, // 仅此必须项
            'showScriptName' => false,
            'enableStrictParsing' => false,

            // 可选项, 添加一些特殊选项，个人不是很推荐大量使用，而应该只是针对特殊情况
            'rules' => [
                // code here
                'posts' => 'post/index',

                // http://yii.test:8080/test/100
                'test/<id:\d+>' => 'test/view',

                // http://yii.test:8080/test/index?id=100
                // http://yii.test:8080/test/index/100
                'test/index/<id:\w+>' => 'test/index',

                // ...其它 URL 规则...
                [
                    'pattern' => 'posts',
                    'route' => 'posts/index',
                    'suffix' => '.json',
                ],
            ],
        ],
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

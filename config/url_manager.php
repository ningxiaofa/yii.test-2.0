<?php

use yii\web\UrlNormalizer;

return [
    'enablePrettyUrl' => true, // 仅此必须项
    'showScriptName' => false,
    'enableStrictParsing' => false,

    // http://yii.test:8080/test/index/100.html 一定要加 .html, 否则报 page not found
    // 'suffix' => '.html', // 你可以在URL后面添加 .html 让其看起来像是一个 HTML 页面；

    //  默认 UrlManager::$normalizer 规范器是关闭的。你需要明确配置其开启 来启用 URL 规范化。
    'normalizer' => [
        'class' => 'yii\web\UrlNormalizer',
        // 调试时使用临时跳转代替永久跳转
        'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
    ],

    // 可选项, 添加一些特殊选项，个人不是很推荐大量使用，而应该只是针对特殊情况
    'rules' => [

        // 结合着url规范器一起看
        // ...其它规则...
        // http://yii.test:8080/pictures/ 可访问
        // http://yii.test:8080/pictures 访问不到
        [
            'pattern' => 'pictures',
            'route' => 'picture/index',
            'suffix' => '/',
            'normalizer' => false, // 针对此规则关闭规范器，即认为两个url
        ],
        [
            'pattern' => 'tags',
            'route' => 'tag/index',
            'normalizer' => [
                // 针对此规则, 不合并连续的斜线
                'collapseSlashes' => false,
            ],
        ],


        // http://yii.test:8080/test/100
        'test/<id:\d+>' => 'test/view',

        // http://yii.test:8080/test/index?id=100
        // http://yii.test:8080/test/index/100
        'test/index/<id:\w+>' => 'test/index',

        // 命名参数
        'posts/<year:\d{4}>/<category>' => 'post/index',
        // 'posts' => 'post/index',
        'post/<id:\d+>' => 'post/view',

        // 参数化路由
        '<controller:(post|comment)>/create' => '<controller>/create',
        '<controller:(post|comment)>/<id:\d+>/<action:(update|delete)>' => '<controller>/<action>',
        '<controller:(post|comment)>/<id:\d+>' => '<controller>/view',
        // '<controller:(post|comment)>s' => '<controller>/index',

        // 默认参数值
        // ...其它 URL 规则...
        [
            'pattern' => 'posts/<page:\d+>/<tag>',
            'route' => 'post/list',
            'defaults' => ['page' => 1, 'tag' => ''],
        ],

        // 可以用来下载文件
        // URL 后缀
        // 
        [
            'pattern' => 'posts',
            'route' => 'posts/index',
            'suffix' => '.json', // or 'suffix' => '.html',
        ],

        // 带服务名称的规则 -- 存在问题，TBD
        // 'admin.yii.test:8080/login' => 'admin/user/login',
        // 'yii.test:8080/login' => 'site/login',

        // HTTP 方法 -- Restful api
        // 期间出现问题，一直提示404，以及找不到该文件
        // 经过排查发现，是创建的文件没有加载到容器中，解决办法，删除从来，还是不行，
        // 最后解决办法，是到容器中，手动创建，然后编辑，或者在物理机中编辑，便正常运行
        'PUT,POST blog/<id:\d+>' => 'blog/update',
        'DELETE blog/<id:\d+>' => 'blog/delete',
        'blog/<id:\d+>' => 'blog/view',

        // 创建规则类
        // 注意：这个路由，很容易匹配到本来是其他的路由，顺序这一点要注意
        [
            'class' => 'app\components\CarUrlRule',
            // ...配置其它参数...
        ],
    ],
];

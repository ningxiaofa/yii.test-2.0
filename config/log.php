<?php

return [
    // 第一个目标选择的是错误和警告层级的消息，并且在数据库表里保存他们；
    // 第二个目标选择的是错误层级的消息并且是在以 yii\db\ 开头的分类下，并且在一个邮件里将它们发送到 admin@example.com 和 developer@example.com。
    'targets' => [
        // ------------------ 这里的db，email，file的key都是可以省略的，但是推荐加上key name
        // // 一个 database target 目标导出已经过滤的日志消息到一个数据的表里面， 
        'db' => [
            'class' => 'yii\log\DbTarget',
            'levels' => ['error', 'warning'],
        ],
        // // 而一个 email target目标将日志消息导出到指定的邮箱地址里。
        'email' => [
            'class' => 'yii\log\EmailTarget',
            'levels' => ['error'],
            'categories' => ['yii\db\*'],
            'message' => [
               'from' => ['log@example.com'],
               'to' => ['admin@example.com', 'developer@example.com'],
               'subject' => 'Database errors at example.com',
            ],
        ],

        // 这是Yii 默认的配置 -- 之前公司用的就是file log方式，似乎也只能用这种方式，yii是否支持写入日志到redis中？TBD
        'file' => [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            // 'levels' => ['error', 'warning', 'info'], // 如果不添加info，那么调用Yii::info是不会写入到app.log文件中的，默认都是写入到app.log中，应可配置。

            // levels 属性是由一个或者若干个以下值组成的数组：
            //     error：相应的消息通过 Yii::error() 被记录。
            //     warning：相应的消息通过 Yii::warning() 被记录。
            //     info：相应的消息通过 Yii::info() 被记录。
            //     trace：相应的消息通过 Yii::trace() 被记录。
            //     profile：相应的消息通过 Yii::beginProfile() 和 Yii::endProfile() 被记录。更多细节将在 Profiling 分段解释。

            // 'categories',数组，没有指定 categories 属性， 这意味着目标将会处理 任何 分类的消息。
            // 'categories' => [
            //     'yii\db\*',
            //     'yii\web\HttpException:*',
            // ],

            // 除了通过 categories 属性设置白名单分类，你也可以通过 except 属性来设置某些分类作为黑名单。
            // 'except' => [
            //     'yii\web\HttpException:404',
            // ],

            // 如果你没有指定 levels 的属性， 那就意味着目标将处理 任何 严重程度的消息。

            // 你可以通过配置 yii\log\Target::$prefix 的属性来自定义格式，这个属性是一个 PHP 可调用体返回的自定义消息前缀。 例如，下面的代码配置了一个日志目标的前缀是每个日志消息中当前用户的 ID（IP 地址和 Session ID 被删除是由于隐私的原因）。
            'prefix' => function ($message) {
                $user = Yii::$app->has('user', true) ? Yii::$app->get('user') : null;
                $userID = $user ? $user->getId(false) : '';
                return "[$userID]"; // 返回的是字符串，也就意味着可以拼接
            },

            // 除了消息前缀以外，日志目标也可以追加一些[上下文信息]到每组日志消息中。 
            // 默认情况下，这些全局的PHP变量的值被包含在：$_GET，$_POST，$_FILES，$_COOKIE，$_SESSION 和 $_SERVER 中。 你可以通过配置 yii\log\Target::$logVars 属性适应这个行为， 这个属性是你想要通过日志目标包含的全局变量名称。 
            // 举个例子，下面的日志目标配置指明了只有 $_SERVER 变量的值将被追加到日志消息中。
            // 'logVars' => ['_SERVER'], // 如果省略该属性，则是将$_GET，$_POST，$_FILES，$_COOKIE，$_SESSION 和 $_SERVER全部写入到了日志中.
            // 可以将 logVars 配置成一个空数组来完全禁止上下文信息包含。 
            // 'logVars' => [] // 设置为数组，则不会写入任何全局变量到日志文件中
            // 或者假如你想要实现你自己提供上下文信息的方式， 你可以重写 yii\log\Target::getContextMessage() 方法。

            // 导出
            // 'exportInterval' => 100,  // default is 1000
        ],

        'profile' => [
            'class' => 'yii\log\FileTarget',
            // 'categories' => ['app\controllers\*'],
            // 'levels' => ['error', 'warning', 'info'],
            // 'logVars' => [],
        ],
    ],

    // 消息跟踪级别 ¶
    // YII_DEBUG 开启则是3，否则是0。 
    // 这意味着，假如 YII_DEBUG 开启，每个日志消息在日志消息被记录的时候， 将被追加最多3个调用堆栈层级；
    // 假如 YII_DEBUG 关闭， 那么将没有调用堆栈信息被包含。
    'traceLevel' => YII_DEBUG ? 3 : 0,
    // 注意： 获得调用堆栈信息并不是不重要。因此， 你应该只在开发或者调试一个应用的时候使用这个特性。

    // 消息刷新和导出 ¶ --- 这里的刷新喝导出不是一回事儿，而且刷新的层级更高，导出是针对每个日志/分类【在target里面】的设置。
    //  如上所述，通过 logger object 对象，。 为了这个数组的内存消耗，当数组积累了一定数量的日志消息， 日志对象每次都将刷新被记录的消息到 log targets 中。 你可以通过配置 log 组件的 flushInterval 属性来自定义数量：
    'flushInterval' => 100,   // default is 1000
    // 注意： 当应用结束的时候，消息刷新也会发生，这样才能确保日志目标能够接收完整的日志消息。

    // 因为刷新和导出层级的设置，默认情况下，当你调用 Yii::trace() 或者任何其他的记录方法，你将不能在日志目标中立即看到日志消息。 这对于一些长期运行的控制台应用来说可能是一个问题。为了让每个日志消息在日志目标中能够立即出现， 你应该设置 flushInterval 和 exportInterval 都为1， 就像下面这样：
    // return [
    //     'bootstrap' => ['log'],
    //     'components' => [
    //         'log' => [
    //             'flushInterval' => 1,
    //             'targets' => [
    //                 [
    //                     'class' => 'yii\log\FileTarget',
    //                     'exportInterval' => 1,
    //                 ],
    //             ],
    //         ],
    //     ],
    // ];
    // 注意： 频繁的消息刷新和导出将降低你的应用性能。 -- 不推荐这种方式，不过还是结合业务场景来说吧

    // 切换日志目标
    // 你可以通过配置 enabled 属性来开启或者禁用日志目标。 
    // 你可以通过日志目标配置去做，或者是在你的代码中放入下面的PHP申明：
    // Yii::$app->log->targets['file']->enabled = false;
    // 上面的代码要求您将目标命名为 file，像下面展示的那样， 在 targets 数组中使用字符串键：
    
    // log配置文件，推荐还是使用如下命名的方式
    // return [
    //     'bootstrap' => ['log'],
    //     'components' => [
    //         'log' => [
    //             'targets' => [
    //                 'file' => [
    //                     'class' => 'yii\log\FileTarget',
    //                 ],
    //                 'db' => [
    //                     'class' => 'yii\log\DbTarget',
    //                 ],
    //             ],
    //         ],
    //     ],
    // ];
];

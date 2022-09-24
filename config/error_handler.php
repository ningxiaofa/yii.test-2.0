<?php

return [
    // 异常页面最多显示20条源代码
    'maxSourceLines' => 20,

    // 使用错误动作 -- 一般使用yii默认就好
    // errorAction 属性使用 路由到一个操作， 上述配置表示不用显示函数调用栈信息的错误会通过执行site/error操作来显示。
    // 'errorAction' => 'site/error', // yii推荐的错误处理动作
    // 'errorAction' => 'error-handler/error',
];
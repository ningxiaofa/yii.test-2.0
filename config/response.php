<?php

return [
    'class' => 'yii\web\Response',

    // 填上这个配置，on beforeSend事件，响应内容样式就有些变化了，不是很美观，易读, 而且正常的路由响应都出了问题，
    // 原因&解决办法 TBD ------------- 需要结合响应的格式为json才可以，比如在控制器动作中设置json响应格式： Yii::$app->response->format = Response::FORMAT_JSON; 或者修改app响应格式为json，默认为html
    // 配置beforeSend事件来自定义错误响应格式 --------- 至于这种方式什么时候，应是后端接口统一使用json作为响应格式
    // 'on beforeSend' => function ($event) {
    //     $response = $event->sender;
    //     if ($response->data !== null) {
    //         $response->data = [
    //             'success' => $response->isSuccessful,
    //             'data' => $response->data,
    //         ];
    //         $response->statusCode = 200;
    //     }
    // },
];
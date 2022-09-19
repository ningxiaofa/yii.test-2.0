<?php

return [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => 'yNcsx_czEx5Hb1Oh93nzF34eUOiVlU5Q',

    // ...


    // Added by self
    // 用来获取客户端的IP，这里主要是解决应用存在反向代理的情况下，如果不存在，那么配置不配置都没影响: TBD
    // ---------------------------------- start
    'trustedHosts' => [
        '172.18.0.0/16' => [
            'X-ProxyUser-Ip',
            'Front-End-Https',
        ],
    ],
    'secureHeaders' => [
        'X-Forwarded-For',
        'X-Forwarded-Host',
        'X-Forwarded-Proto',
        'X-Proxy-User-Ip',
        'Front-End-Https',
    ],
    'ipHeaders' => [
        'X-Proxy-User-Ip',
    ],
    'secureProtocolHeaders' => [
        'Front-End-Https' => ['on']
    ],
    // ---------------------------------- end
];
<?php
return array(
    'DB_TYPE'                => 'mysql', // 数据库类型
    'DB_HOST'                => '127.0.0.1', // 服务器地址
    'DB_NAME'                => 'shop1009', // 数据库名
    'DB_USER'                => 'root', // 用户名
    'DB_PWD'                 => 'admin', // 密码
    'DB_PORT'                => '', // 端口
    'DB_PREFIX'              => '', // 数据库表前缀
    'DB_PARAMS'              => array(), // 数据库连接参数
    'DB_DEBUG'               => true, // 数据库调试模式 开启后可以记录SQL日志

    'DATA_CACHE_TYPE'        => 'Redis',  //指定缓存为Redis
    'REDIS_HOST'             => '127.0.0.1',   //指定redis的服务器地址
    'REDIS_PORT'             => 6379,           //指定redis的服务器端口

    //短信应用的配置
    'SMS_CONFIG'             =>array(
        'appkey' => '23268328',
        'secretKey' => 'a7bd32620043c504d76564c8badcc1ad'
    ),
    'MAIL_CONFIG'            =>array(
        'Host'=>            'smtp.126.com',
        'Username'=>        'itsource520@126.com',
        'Password'=>        'qqitsource520',
        'From'=>            'itsource520@126.com'
    )
);
<?php

// https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-sessions-cookies
// 自定义 Session 存储

// yii\web\Session 类默认存储 session 数据为文件到服务器上， Yii 提供以下 session 类实现不同的 session 存储方式：

// yii\web\DbSession：存储 session 数据在数据表中
// yii\web\CacheSession：存储 session 数据到缓存中，缓存和配置中的缓存组件相关
// yii\redis\Session：存储 session 数据到以 redis 作为存储媒介中
// yii\mongodb\Session：存储 session 数据到 MongoDB。
// 所有这些 session 类支持相同的 API 方法集，因此， 切换到不同的 session 存储介质不需要修改项目使用 session 的代码。


// 默认yii\web\Session 类默认存储 session 数据为文件到服务器上
return [
    // nothing to code
];

// 数据表-----------------------------------------------------------------------start
return [
    'class' => 'yii\web\DbSession',
    'db' => 'mydb',  // 数据库连接的应用组件ID，默认为'db'.
    'sessionTable' => 'my_session', // session 数据表名，默认为'session'.
];

// 需要创建如下数据库表来存储 session 数据：
// CREATE TABLE session
// (
//     id CHAR(40) NOT NULL PRIMARY KEY,
//     expire INTEGER,
//     data BLOB
// )

// 其中 'BLOB' 对应你选择的数据库管理系统的 BLOB-type 类型，以下一些常用数据库管理系统的 BLOB 类型：

// MySQL: LONGBLOB
// PostgreSQL: BYTEA
// MSSQL: BLOB

// 注意： 根据 php.ini 设置的 session.hash_function，你需要调整 id 列的长度， 例如，如果 session.hash_function=sha256， 应使用长度为 64 而不是 40 的 char 类型。

// 或者，可以通过以下迁移完成：
// <?php

// use yii\db\Migration;

// class m170529_050554_create_table_session extends Migration
// {
//     public function up()
//     {
//         $this->createTable('{{%session}}', [
//             'id' => $this->char(64)->notNull(),
//             'expire' => $this->integer(),
//             'data' => $this->binary()
//         ]);
//         $this->addPrimaryKey('pk-id', '{{%session}}', 'id');
//     }

//     public function down()
//     {
//         $this->dropTable('{{%session}}');
//     }
// }
// 数据表-----------------------------------------------------------------------end

// 缓存: 存储 session 数据到缓存中，缓存和配置中的缓存组件相关
return [
    'class' => 'yii\web\CacheSession',
    // other configures，TBD
];

// Redis: 存储 session 数据到以 redis 作为存储媒介中
return [
    'class' => 'yii\redis\Session',
    // other configures，TBD
];

// MongoDB: 存储 session 数据到 MongoDB。
return [
    'class' => 'yii\mongodb\Session',
    // other configures，TBD
];
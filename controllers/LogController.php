<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;

// https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-logging
class LogController extends Controller
{
    // 日志消息
    public function actionIndex()
    {
        // code here
        Yii::debug('start calculating average revenue', __METHOD__); // 没有写入日志文件中，因为配置的关系，debug不在level中

        // 默认的配置，只有error，warning会写入到日志文件中
        Yii::info('This is a info', __METHOD__); // 可以添加info，那么也会写入
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 消息过滤
    // 对于每一个日志目标，你可以配置它的 levels 和 categories 属性来指定哪个消息的严重程度和分类目标应该处理。
    public function actionMessageFilter()
    {
        // 只要看log.php配置文件即可，简单说，就是通过level，category，except属性来设置
        Yii::info('This is a info', __METHOD__);
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 消息格式化
    // 日志目标以某种格式导出过滤过的日志消息
    // 默认情况下，日志消息将被格式化，格式化的方式遵循 yii\log\Target::formatMessage()：
    // Timestamp [IP address][User ID][Session ID][Severity Level][Category] Message Text

    public function actionMessageFormat()
    {
        // 依然是设置log配置，然后请求接口，查看日志格式的变化
        Yii::info('This is a info', __METHOD__);
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 消息跟踪级别
    public function actionMessageTraceLevel()
    {
        // 依然是设置log配置，然后请求接口，查看日志格式的变化
        Yii::info('This is a info', __METHOD__);
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 消息刷新和导出
    public function actionMessageRefreshAndExport()
    {
        // 依然是设置log配置，然后请求接口，查看日志格式的变化
        Yii::info('This is a info', __METHOD__);
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 切换日志目标
    // http://yii.test:8080/log/switch-log-target
    public function actionSwitchLogTarget()
    {
        // 你可以通过配置 enabled 属性来开启或者禁用日志目标。 
        // 你可以通过日志目标配置去做，或者是在你的代码中放入下面的PHP申明：
        Yii::$app->log->targets['file']->enabled = false;
        // 上面的代码要求您将目标命名为 file，像下面展示的那样， 在 targets 数组中使用字符串键：// log配置文件，推荐还是使用如下命名的方式
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
        
        // 下面的日志调用，都不会写入到app.log日志文件中
        Yii::info('This is a info', __METHOD__);
        Yii::warning('This is a warning', __METHOD__);
        Yii::error('This is a error', __METHOD__);
    }

    // 创建新的目标
    // 创建一个新的日志目标类非常地简单。你主要需要实现 yii\log\Target::export() 方法来发送 yii\log\Target::$messages 数组的 内容到一个指定的媒体中。你可以调用 yii\log\Target::formatMessage() 方法去格式化每个消息。 更多细节，你可以参考任何一个包含在 Yii 发行版中的日志目标类。
    // 提示： 您可以使用 PSR log target extension 尝试任何兼容 PSR-3 的日志记录器， 例如 Monolog， 而不是创建自己的日志记录器。

    // 所以，应该是可以写入到Redis，memcached，ES中的，具体如何做，TBD
    public function actionCreateNewTarget()
    {
        // TBD
    }
    
    // 性能分析
    // 性能分析是一个特殊的消息记录类型，它通常用在测量某段代码块的时间， 并且找出性能瓶颈是什么。举个例子，yii\db\Command 类 使用性能分析找出每个数据库查询的时间。
    // 为了使用性能分析，首先确定需要进行分析的代码块。 然后像下面这样围住每个代码块：
    public function actionProfile()
    {
        // 这里的 myBenchmark 代表一个唯一标记来标识一个代码块。
        // 之后当你检查分析结果的时候， 你将使用这个标记来定位对应的代码块所花费的时间。
        // 对于确保 beginProfile 和 endProfile 对能够正确地嵌套，这是很重要的。 例如，
        Yii::beginProfile('myBenchmark');

        // ...code block being profiled...
        $ret = 0;
        for($i = 0; $i < 10000; $i++){
            $ret += $i;
        }

        Yii::endProfile('myBenchmark');
        return $this->renderContent('just for test');

        Yii::beginProfile('block1');
            // some code to be profiled$ret = 0;
            $ret = 0;
            for($i = 0; $i < 10000; $i++){
                $ret += $i;
            }

            Yii::beginProfile('block2');
                // some other code to be profiled
                $ret = 0;
                for($i = 0; $i < 10000; $i++){
                    $ret += $i;
                }
            Yii::endProfile('block2');

            $ret = 0;
            for($i = 0; $i < 10000; $i++){
                $ret += $i;
            }

        Yii::endProfile('block1');

        // 假如你漏掉 \Yii::endProfile('block1') 或者切换了 \Yii::endProfile('block1') 和 \Yii::endProfile('block2') 的 顺序，那么性能分析将不会工作。
        // 对于每个被分析的代码块，一个带有严重程度为 profile 的日志消息将被记录。 
        // 你可以配置一个 log target 去收集这些 消息，并且导出他们。
        // Yii debugger 有一个内建的性能分析面板能够展示分析结果。 --------- 如何使用，TBD
        // 似乎yii basic模版，在开发环境中，默认已经开启
        // http://yii.test:8080/debug/default/index?page=1&per-page=50

        // Note: 只能输出视图【html】时，才能看到debug data/性能分析面板
        return $this->renderContent('just for test');
        // http://yii.test:8080/debug/default/view?tag=63308b9bd5088&panel=profiling
    }
}

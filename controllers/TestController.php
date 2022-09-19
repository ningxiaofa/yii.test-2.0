<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

class TestController extends Controller
{
    // 设置csrf验证为false，否则一直报错: yii Unable to verify your data submission.
    // 但是不推荐，因为不安全，这里是临时使用
    public $enableCsrfValidation = false;

    /**
     * Just test beauty url
     *
     * @return string
     */
    public function actionView(int $id)
    {
        var_dump($id);
        exit;
    }

    // http://yii.test:8080/test/index?id=100
    // 
    public function actionIndex(string $id)
    {
        var_dump($id);
    }

    public function actionHead()
    {
        // $headers 是一个 yii\web\HeaderCollection 对象
        $headers = Yii::$app->request->headers;
        // 返回 Accept header 值
        $accept = $headers->get('Accept'); //  string(3) "*/*"
        var_dump($accept);

        if ($headers->has('User-Agent')) {
            var_dump('这是一个 User-Agent 头'); // string(27) "这是一个 User-Agent 头"
        }
    }

    public function actionGet()
    {
        $request = Yii::$app->request;
        // var_dump($request);

        // Host[主机名/域名，通常客户端请求过来没有带主机名]
        $userHost = $request->userHost;
        $userIP = Yii::$app->request->userIP;
        var_dump($userHost, $userIP); // NULL string(10) "172.18.0.1" 为什么是这个？「应是因为从物理机访问容器应用，请求先是到达容器网络的网关 172.18.0.1，然后到达nginx，所以可以看到如下信息」
        // ["REMOTE_ADDR"]=>string(10) "172.18.0.1"
        // ["REMOTE_PORT"]=>string(5) "61242"
        // ["SERVER_ADDR"]=>string(10) "172.18.0.5"

        // Docker容器中的信息
        // Docker inspect container-id
        // "Gateway": "172.18.0.1",
        // "IPAddress": "172.18.0.5",
        // "IPPrefixLen": 16, -- 子网掩码

        // /run-nginx-latest - 172.18.0.5
        // /run-php-74-fpm - 172.18.0.4
        // /run-mysql-80 - 172.18.0.3
        // /run-redis-latest - 172.18.0.2

        // ----------------------------------- 测试获取客户端IP
        // $ip = $this->getIP();
        // var_dump($ip);
        // exit;

        // 获取cookie
        $cookie = $request->cookies;
        var_dump($cookie);

        // 判断请求方式
        $ret = $this->judgeHttpMethod();
        var_dump($ret);
        exit;
    }

    // yii.test:8080/test/post?name=william
    public function actionPost()
    {
        $request = Yii::$app->request;
        $ret = $this->judgeHttpMethod();
        // var_dump($ret);

        // 获取 $_POST中的请求数据，但是没办法获取content-type: application/json的请求参数
        // 因为是放在变量 $HTTP_RAW_POST_DATA中
        $post = $request->post();
        $name = $request->post('name'); // 获取不到查询字符串中name的值
        $name = $request->get('name'); // 能获取到查询字符串中name的值
        var_dump($post, $name);

        // 获取所有参数 -- 还是请求体中的参数
        $params = $request->bodyParams;
        var_dump($params);

        // 获取content-type: application/json的请求参数
        // 如果请求内容类型不是application/json，那么获取的内容是字符串拼接形式: string(38) "_csrf=AHVu8fHOpYvZqvz50XCEi736qxI3alUG"
        $post = file_get_contents("php://input");
        var_dump($post);

        // 
    }

    public function actionPut()
    {
        $request = Yii::$app->request;
        $ret = $this->judgeHttpMethod();
        var_dump($ret);

        // userAgent
        $userAgent = $request->userAgent;
        $contentType = $request->contentType;
        $acceptableContentTypes = $request->acceptableContentTypes;
        $acceptableLanguages = $request->acceptableLanguages;

        var_dump($userAgent, $contentType, $acceptableContentTypes, $acceptableLanguages);
        // string(21) "PostmanRuntime/7.29.2"

        // string(0) "" 或者 string(16) "application/json" -- 注意：当即便选了application/json但是没有传递参数，依然是""

        // array(1) {
        // ["*/*"]=>
        // array(1) {
        //     ["q"]=>
        //     int(1)
        // }
        // }

        // array(0) {
        // }
    }

    public function actionDelete()
    {
        $request = Yii::$app->request;
        $ret = $this->judgeHttpMethod();
        var_dump($ret);
    }

    protected function judgeHttpMethod()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) { // 这是怎么判断的？TBD
            return '该请求是一个 AJAX 请求';
        }
        if ($request->isGet) {
            return '请求方法是 GET';
        }
        if ($request->isPost) {
            return '请求方法是 POST';
        }
        if ($request->isPut) {
            return '请求方法是 PUT';
        }
    }

    protected function getIP()
    {
        var_dump(getenv());
        exit;
        //strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $res = preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
        echo $res;
        //dump(phpinfo());//所有PHP配置信息
    }


    // https://www.yiiframework.com/doc/guide/2.0/zh-cn/runtime-responses
    // 响应
    public function actionJson()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'message' => 'hello world',
            'code' => 100,
        ];
    }

    public function actionXml()
    {
        Yii::$app->response->format = Response::FORMAT_XML;

        return [
            'message' => 'hello world',
            'code' => 100,
        ];
    }

    public function actionRaw()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;

        return json_encode([
            'message' => 'hello world',
            'code' => 100,
        ]);
    }

    // 注意： 如果创建你自己的响应对象，将不能在应用配置中设置 response 组件，尽管如此， 可使用 依赖注入 应用通用配置到你新的响应对象 -- 还是推荐使用默认的 response 应用组件
    public function actionNewResponse()
    {
        $confArr = [
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_JSON,
            'data' => [
                'message' => 'hello world',
                'code' => 100,
            ],
        ];

        return Yii::createObject($confArr);
    }

    // 浏览器跳转
    public function actionOld()
    {
        // 正常运行 -- 浏览器
        // 使用Postman也是正常运行，只不过看不到重定向的过程，而是直接返回新接口的响应内容
        // return $this->redirect('/test/new', 301);

        // 有问题，原因应是没有明确指明http的协议, 到底是使用http还是https
        // return $this->redirect('yii.test:8080/test/new', 301); // 错误用法

        // 应该使用下面的方式
        // return $this->redirect('http://yii.test:8080/test/new', 301);

        // 或者
        Yii::$app->response->redirect('http://yii.test:8080/test/new', 301)->send();


        // 如果当前请求为 AJAX 请求，发送一个 Location 头不会自动使浏览器跳转，为解决这个问题， yii\web\Response::redirect() 方法设置一个值为要跳转的URL的 X-Redirect 头， 在客户端可编写 JavaScript 代码读取该头部值然后让浏览器跳转对应的 URL。

    }

    public function actionNew()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'message' => 'this is new api',
            'code' => 100,
        ];
    }

    // ----------------------------分割线-----------------------
    // 发送文件
    // 和浏览器跳转类似，文件发送是另一个依赖指定 HTTP 头的功能， Yii 提供方法集合来支持各种文件发送需求，它们对 HTTP 头都有内置的支持。

    // yii\web\Response::sendFile()：发送一个已存在的文件到客户端
    // yii\web\Response::sendContentAsFile()：发送一个文本字符串作为文件到客户端
    // yii\web\Response::sendStreamAsFile()：发送一个已存在的文件流作为文件到客户端

    // 这些方法都将响应对象作为返回值，如果要发送的文件非常大，应考虑使用 yii\web\Response::sendStreamAsFile() 因为它更节约内存， 以下示例显示在控制器操作中如何发送文件：
    
    // 已有文件
    public function actionDownload()
    {
        $storage = Yii::getAlias('@app/storage');
        return \Yii::$app->response->sendFile($storage . '/posts.json');
    }

    // 大文件下载的推荐方式
    public function actionSendStreamAsFile()
    {
        $storage = Yii::getAlias('@app/storage');
        return \Yii::$app->response->sendFile($storage . '/posts.json');
    }

    // 如果不是在操作方法中调用文件发送方法，在后面还应调用 yii\web\Response::send() 没有其他内容追加到响应中。
    // 浏览器还是下载文件，有点困惑 -- TBD
    public function actionSendContent()
    {
        $storage = Yii::getAlias('@app/storage');
        return Yii::$app->response->sendFile($storage . '/posts.json')->send();
    }

    // 将内容输出为文件下载
    public function actionSendContentAsFile()
    {
        $content = 'hello world';
        return Yii::$app->response->sendContentAsFile($content, 'any-name.txt');
    }

    // 发送响应
    // 在 yii\web\Response::send() 方法调用前，响应中的内容不会发送给用户， 
    // 该方法默认在 yii\base\Application::run() 结尾自动调用，尽管如此，可以明确调用该方法强制立即发送响应。
    public function actionSend()
    {
        // 只能为基本数据类型
        $content = 'hello world';
        // $content = true;
        // $content = 123;
        // $content = 12.34;
        // $content = null; // 输出为空，啥都没有，即不可打印字符

        // $content = [
        //     'just for test'
        // ]; // Invalid Argument – yii\base\InvalidArgumentException 
        // // Response content must not be an array.

        return $content;
    }

    public function actionSendNow()
    {
        $response =  Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = [
            'message' => 'yep, send now',
            'code' => 100,
        ];

        return Yii::$app->response->send();
    }
    // 一旦 yii\web\Response::send() 方法被执行后，其他地方调用该方法会被忽略， 这意味着一旦响应发出后，就不能再追加其他内容。
}

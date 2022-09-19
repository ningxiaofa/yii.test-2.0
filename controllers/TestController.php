<?php

namespace app\controllers;

use yii\web\Controller;
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
}

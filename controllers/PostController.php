<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

// 所有的路由规则参见，web.php中components.urlManager「可以将其独立出去，为单独的文件，参考db.php params.php」

class PostController extends Controller
{
    // http://yii.test:8080/posts
    // 正常下载文件
    public function actionIndex()
    {
        // 这里是直接放在了web root目录，可以直接被浏览访问[ttp://yii.test:8080/json/posts.json, 展示json内容]，不太安全 -- 应该是情况而定
        $postJsonFile = Yii::getAlias('@webroot') . '/json/posts.json';
        return Yii::$app->response->sendFile($postJsonFile, 'post-rename.json');
    }

    // 对访问安全有要求，推荐放到非Web root 目录下，不可被直接浏览器访问到.
    // http://yii.test:8080/post/download?filename=posts.json
    public function actionDownload(string $filename)
    {
        $storagePath = Yii::getAlias('@app/storage');

        // check filename for allowed chars 
        // (do not allow ../ to avoid security issue: 
        // downloading arbitrary files)
        if (!preg_match('/^[a-z0-9]+\.[a-z0-9]+$/i', $filename) || !is_file("$storagePath/$filename")) {
            throw new NotFoundHttpException('The file does not exists.');
        }
        return Yii::$app->response->sendFile("$storagePath/$filename", $filename);
    }

    // 这里的默认值是不起作用的，因为前端请求的参数会覆盖
    public function actionList($page = 1, $tag = 'php') 
    {
        var_dump($page, $tag); 
        // http://yii.test:8080/posts ==> int(1) string(0) ""
        // http://yii.test:8080/posts/php ==> int(1) string(3) "php"
        // http://yii.test:8080/posts/2/php ==> string(1) "2" string(3) "php"
    }

    // 仅仅支持PUT,POST请求方法
    public function actionUpdate($id)
    {
        var_dump($id);
    }

    public function actionView($id)
    {
        var_dump($id); 
    }

}

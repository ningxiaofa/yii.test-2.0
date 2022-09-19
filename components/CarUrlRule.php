<?php

namespace app\components;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

class CarUrlRule extends BaseObject implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'car/index') {
            if (isset($params['manufacturer'], $params['model'])) {
                return $params['manufacturer'] . '/' . $params['model'];
            } elseif (isset($params['manufacturer'])) {
                return $params['manufacturer'];
            }
        }
        return false; // this rule does not apply
    }

    // http://yii.test:8080/ford/evos
    // 可以看到ford/evos 的value可以任意改变，也就是路由是动态变化的
    // 返回来createUrl 生成的url也是可以动态变化的，但是前提是 潜在的route == car/index，
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
            // 检查 $matches[1] 和 $matches[3]
            // 确认是否匹配到一个数据库中保存的厂家和型号。
            // 如果匹配，设置参数 $params['manufacturer'] 和 / 或 $params['model']
            // 返回 ['car/index', $params]

            // Test
            $params['manufacturer'] = $matches[1] ?? 'ford';
            $params['model'] = $matches[3] ?? 'modiou';
            return ['car/index', $params];
        }

        return false; // 本规则不会起作用
    }
}

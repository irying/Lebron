<?php


namespace app\core\traits\action;


use app\core\codes\Api;
use app\core\utils\Center;
use app\core\utils\Response;
use app\modules\v3\utils\CommonUtil;
use yii;

/**
 * 检查客户端是否提供了必要的参数
 *
 * @package app\core\traits\action
 */
trait VerifyRequireParam
{
    protected function verifyRequireParamBeforeRun()
    {
        if (is_callable([$this, 'getRequireParams']) && is_callable([$this, 'getBody'])) {
            $requiredParams = call_user_func([$this, 'getRequireParams']);
            $body = call_user_func([$this, 'getBody']);

            $isMatch = CommonUtil::getInstance()->isMatchRequireParam(array_keys($body), $requiredParams);
            if (empty($body) || $isMatch === false) {
                $msg = Center::t('code/api', Api::PARAM_MISSING, ['{param}' => implode(',', $requiredParams)]);
                Yii::info(serialize(Response::getInstance()->get(Api::PARAM_MISSING, null, $msg)), 'BusinessLog');
                $resp = Yii::$app->getResponse();
                $resp->data = Response::getInstance()->get(Api::PARAM_MISSING, null, $msg);
                $resp->send();
                return false;
            }
        }

        return true;
    }
}
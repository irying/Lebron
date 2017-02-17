<?php


namespace app\core\traits\action;

use app\core\codes\Api;
use app\core\utils\Center;
use app\core\utils\Response;
use app\modules\v3\utils\AuthVerify;
use yii;

/**
 * 在需要签名认证的 Action 中使用
 *
 * @package app\core\traits
 */
trait VerifySignBeforeRun
{
    protected function verifySignBeforeRun()
    {
        if (!AuthVerify::isSignValid()) {
            Yii::info(array('msg' => Center::t('code/api', Api::SIGN_ERROR)), 'BusinessLog');
            $resp = Yii::$app->getResponse();
            $resp->data = Response::getInstance()->get(Api::SIGN_ERROR);
            $resp->send();
            return false;
        }

        return true;
    }
}

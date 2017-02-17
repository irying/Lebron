<?php


namespace app\core\traits\action;


use app\core\codes\Api; 
use app\core\utils\Response;
use app\modules\v3\models\BaseToken;
use app\modules\v3\models\users\User;
use app\modules\v3\utils\AuthVerify;
use yii;
use yii\db\ActiveRecord;

trait VerifyPersonalAuthBeforeRun
{
    /**
     * @var ActiveRecord 用户信息（数据来自 User 表）
     */
    protected $identity;

    protected function verifyPersonalAuthBeforeRun()
    {
        $resp = yii::$app->getResponse();
        if (AuthVerify::getTokenIsValid(BaseToken::TYPE_PERSONAL) === false) {
            $resp->data = Response::getInstance()->get(AuthVerify::getTokenCode());
            yii::info(serialize($resp->data), 'BusinessLog');
            $resp->send();

            return false;
        }

        $accessToken = AuthVerify::getToken();
        $identity = User::findIdentityByAccessToken($accessToken);
        if (empty($identity)) {
            $resp->data = Response::getInstance()->get(Api::USER_INVALID_IDENTITY);
            yii::info(serialize($resp->data), 'BusinessLog');
            $resp->send();

            return false;
        }

        $this->identity = $identity;

        return true;
    }
}

<?php
/**
 * @link https://api.ibos.cn/
 * @copyright Copyright (c) 2017 IBOS Inc
 */

namespace app\modules\v4\components\traits;

use app\core\codes\Api;
use app\modules\v4\models\Token;
use app\modules\v4\utils\ResponseUtil;
use yii;

trait AccessTokenCheck
{
    /**
     *
     * @var integer
     */
    private $uid = 0;

    /**
     *
     * @var integer
     */
    private $accesstoken;

    /**
     *
     * @return integer
     */
    protected function getUid()
    {
        $this->initUid();
        return $this->uid;
    }

    /**
     *
     * @return string
     */
    protected function getAccessToken()
    {
        return $this->getAccessTokenByParamsOrHeaders();
    }

    /**
     *
     * @throws yii\base\InvalidParamException
     */
    private function initUid()
    {
        if (empty($this->uid)) {
            $accesstoken = $this->getAccessTokenByParamsOrHeaders();
            $token = Token::findOne(['key' => $accesstoken]);
            if (!empty($token)) {
                if ($token->getIsExpiry()) {
                    $response = ResponseUtil::getOutputArrayByCode(Api::TOKEN_EXPIRY);
                    throw new yii\base\InvalidParamException($response['message'], $response['code']);
                }
                $this->uid = $token->uid;
                $this->accesstoken = $accesstoken;
            } else {
                $response = ResponseUtil::getOutputArrayByCode(Api::TOKEN_INVALID);
                throw new yii\base\InvalidParamException($response['message'], $response['code']);
            }
        }
    }

    /**
     * 
     * @return string
     */
    private function getAccessTokenByParamsOrHeaders()
    {
        if (empty($this->accesstoken)) {
            $params = yii::$app->request->getQueryParams();
            $headers = yii::$app->request->getHeaders();
            if (isset($params['accesstoken'])) {
                $accesstoken = $params['accesstoken'];
            } else if (isset($headers['accesstoken'])) {
                $accesstoken = $params['accesstoken'];
            } else {
                $accesstoken = '';
            }
            $this->accesstoken = $accesstoken;
        }
        return $this->accesstoken;
    }
}
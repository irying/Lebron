<?php
/**
 * @link https://api.ibos.cn/
 * @copyright Copyright (c) 2017 IBOS Inc
 */

namespace app\modules\v4\components\traits;

use app\core\codes\Api;
use app\modules\v4\utils\RequestUtil;
use yii;
use yii\base\InvalidParamException;

trait RequireParamCheck
{

    /**
     *
     * @return integer
     */
    protected function checkRequireParam($requireParams)
    {
        $body = RequestUtil::getBody();
        $missingParam = $this->getMissParamFromPostBody($body, $requireParams);
        if (!empty($missingParam)) {
            $message = yii::t('code/api', Api::PARAM_MISSING, ['param' => implode(',', $missingParam)]);
            throw new InvalidParamException($message, Api::PARAM_MISSING);
        }
    }

    /**
     *
     * @param array $requestBody
     * @param array $requireParams
     * @return array
     */
    private function getMissParamFromPostBody($requestBody, $requireParams)
    {
        $taggedArray = static::getParamExistsOrNotArrayByRequireParam($requestBody, $requireParams);
        $missParam = [];
        foreach ($taggedArray as $param => $paramExists) {
            if ($paramExists === false) {
                $missParam[] = $param;
            }
        }
        return $missParam;
    }

    /**
     *
     * @param array $requestBody
     * @param array $requireParams
     * @return array
     */
    private function getParamExistsOrNotArrayByRequireParam($requestBody, $requireParams)
    {
        $filterFunction = function($param) use ($requestBody) {
            $hasMultipleparam = strpos($param, '|') > 0;
            if ($hasMultipleparam) {
                $extractParam = explode('|', $param);
                $exists = false;
                foreach ($extractParam as $temp) {
                    if (isset($requestBody[$temp])) {
                        $exists = true;
                        break;
                    }
                }
                return $exists;
            } else {
                return isset($requestBody[$param]);
            }
        };
        $taggedArray = array_map($filterFunction, $requireParams);
        return array_combine(array_values($requireParams), array_values($taggedArray));
    }
}
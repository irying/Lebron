<?php
/**
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */

namespace core\components;

interface ThirdPartyService extends Api
{

    public function getCallIsSuccess($rawResult);
}
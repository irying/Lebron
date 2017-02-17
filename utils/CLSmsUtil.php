<?php
/**
 * 创蓝短信工具类
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */


use core\components\ThirdPartyService;

class CLSmsUtil extends CurlUtil implements ThirdPartyService
{
    /**
     * 账户
     * @var string
     */
    public $account = '';

    /**
     * 密码
     * @var string
     */
    public $password = '';

    /**
     * 请求地址
     * @var string
     */
    public $url = '';

    /**
     * 要发送的手机号码
     * @var string
     */
    public $mobile = '';

    /**
     * 发送的内容
     * @var string
     */
    public $content = '';

    /**
     * 是否需要状态码
     * @var string
     */
    public $needStatus = 1;

    /**
     * 请求接口
     * @return boolean
     */
    public function request()
    {
        $postStr = $this->getPostDataToStr();
        DebugUtil::traceRequestBegin(['str' => $postStr, 'url' => $this->url], __METHOD__);
        $result = $this->fetch($this->url, $postStr, 'post');
        DebugUtil::traceRequestEnd($result);
        return $this->getCallIsSuccess($result);
    }

    /**
     * 创蓝短信接口返回的是字符串以逗号分隔
     * 前面是时间，后面是状态码  例如 '20150826163033,0'
     * @param mixed $rawResult
     * @return boolean
     */
    public function getCallIsSuccess($rawResult)
    {
        if (!is_string($rawResult)) {
            DebugUtil::info($rawResult, __METHOD__);
            return false;
        } else {
            $return = explode(',', $rawResult);
            if (isset($return['1']) && $return['1'] == 0) {
                return true;
            }
            DebugUtil::info($rawResult, __METHOD__);
            return false;
        }
    }

    /**
     * 将post的数组换成成string
     * @return string
     */
    protected function getPostDataToStr()
    {
        $postData = [
            'un' => $this->account,
            'pw' => $this->password,
            'msg' => $this->content,
            'phone' => $this->mobile,
            'rd' => $this->needStatus
        ];
        $toStr = "";
        foreach ($postData as $k => $v) {
            $toStr .= "$k=".urlencode($v)."&";
        }
        rtrim($toStr, '&');
        return $toStr;
    }
}
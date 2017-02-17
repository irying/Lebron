<?php
/**
 * CURL工具类文件
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */


class CurlUtil extends SystemUtil
{

    public static function getInstance($className = __CLASS__)
    {
        return parent::getInstance($className);
    }
    /**
     * 默认的CURL选项
     * @var array
     */
    protected $curlopt = [
        CURLOPT_RETURNTRANSFER => true, // 返回页面内容
        CURLOPT_HEADER => false, // 不返回头部
        CURLOPT_ENCODING => "", // 处理所有编码
        CURLOPT_USERAGENT => "spider", //
        CURLOPT_AUTOREFERER => true, // 自定重定向
        CURLOPT_CONNECTTIMEOUT => 15, // 链接超时时间
        CURLOPT_TIMEOUT => 30, // 超时时间
        CURLOPT_MAXREDIRS => 10, // 超过十次重定向后停止
        CURLOPT_SSL_VERIFYHOST => 0, // 不检查ssl链接
        CURLOPT_SSL_VERIFYPEER => false, //
        CURLOPT_VERBOSE => 1 //
    ];

    /**
     * 设置curl选项
     * @param array $opt
     */
    public function setOpt($opt)
    {
        if (!empty($opt)) {
            $this->curlopt = $opt + $this->curlopt;
        }
    }

    /**
     * 获取CURL选项
     * @return array
     */
    protected function getOpt()
    {
        return $this->curlopt;
    }

    /**
     * 创建api链接
     * @param string $url 链接地址
     * @param array $param 附件的参数
     * @return string
     */
    public function buildUrl($url, $param = [])
    {
        if (empty($param)) {
            return $url;
        }
        $data = http_build_query($param);
        return $url.(strpos($url, '?') ? '&' : '?').$data;
    }

    /**
     * 获取调用api结果
     * @param string $url api地址
     * @param array $param 如果类型为post时，要提交的参数
     * @param string $type 发送的类型 get or post
     * @return array
     */
    public function fetch($url, $param = [], $type = 'get')
    {
        if ($type == 'post') {
            $this->setOpt([
                CURLOPT_POST => 1, // 是否post提交数据
                CURLOPT_POSTFIELDS => $param, // post的值
            ]);
        } else {
            $url = $this->buildUrl($url, $param);
        }
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->getOpt());
        $result = curl_exec($ch);
        if ($result === false) {
            return [
                'error' => 'error:'.curl_error($ch),
                'errno' => curl_errno($ch),
            ];
        }
        curl_close($ch);
        return $result;
    }
}
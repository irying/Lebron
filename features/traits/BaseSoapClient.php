<?php
/**
 * soap 客户端
 *
 * @namespace app\core\traits
 * @filename BaseSoapClient.php
 * @encoding UTF-8
 * @author zqhong <i@zqhong.com>
 * @link http://www.ibos.com.cn/
 * @copyright Copyright &copy; 2012-2016 IBOS Inc
 * @datetime 2016/9/26 11:08
 */

namespace features\traits;


trait BaseSoapClient
{
    use Singleton;

    /**
     * @var \SoapClient soap 客户端
     */
    protected $soapClient;

    /**
     * 使用 ClassName::method() 且 method 不存在的时候调用.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getInstance()->soapClient->__soapCall($name, $arguments);
    }

    /**
     * 初始化方法
     * 默认认为：wsdl 文件的路径为 /BASE_PATH/data/wsdl/CurrentClassName.wsdl.
     */
    protected function init()
    {
        $reflectClass = new \ReflectionClass($this);
        $wsdlFile = sprintf('%s/data/wsdl/%s.wsdl', dirname(\Yii::$app->getBasePath()), $reflectClass->getShortName());
        $this->soapClient = new \SoapClient($wsdlFile, array());
    }
}
<?php
/**
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */


use yii;

class SystemUtil extends yii\base\Object
{
    /**
     * 单例应用对象
     * @var object 
     */
    private static $instances = [];

    /**
     * 单例化api
     * @return object
     */
    public static function getInstance($className = __CLASS__)
    {
        if (isset(self::$instances[$className])) {
            return self::$instances[$className];
        } else {
            $instance = self::$instances[$className] = new $className();
            return $instance;
        }
    }
}
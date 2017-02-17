<?php
/**
 * 通用单例模式代码
 *
 * @namespace app\core\traits
 * @filename Singleton.php
 * @encoding UTF-8
 * @author zqhong <i@zqhong.com>
 * @link http://www.ibos.com.cn/
 * @copyright Copyright &copy; 2012-2016 IBOS Inc
 * @datetime 2016/9/14 11:09
 */
namespace features\traits;

trait Singleton
{
    protected static $instance;

    final private function __construct()
    {
        $this->init();
    }

    protected function init()
    {
    }

    final public static function getInstance()
    {
        if (isset(static::$instance)) {
            return static::$instance;
        }

        return static::$instance = new static();
    }

    final private function __clone()
    {
    }

    final private function __wakeup()
    {
    }
}

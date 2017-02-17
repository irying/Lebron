<?php
/**
 * 重写 Yii 自带 Migration：
 * 将原本输出到系统输出改为输出到文件
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */



use Ibos\utils\DebugUtil;
use Yii;
use yii\db\Connection;
use yii\db\Migration as YiiMigration;

/**
 * Class Migration
 *
 * @package Ibos\core\components
 */
class Migration extends YiiMigration
{

    /**
     * 获取所有已安装的 SAAS 示例
     *
     * @return array
     */
    protected function allClient()
    {
        $clients = Yii::$app->db->createCommand("SELECT * FROM {{config}} WHERE `installed` = 1")
            ->queryAll();
        return $clients;
    }

    /**
     * 重新设置数据库连接
     * 
     * @param array $dbConfig
     */
    protected function resetDb($dbConfig)
    {
        $db = [
            'dsn' => "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}",
            'username' => $dbConfig['username'],
            'password' => $dbConfig['password'],
            'charset' => $dbConfig['charset'],
            'tablePrefix' => $dbConfig['tableprefix'],
        ];
        $connect = new Connection($db);
        $this->db = $connect;
    }

    /**
     * 写入跟踪日志
     * @param $content
     */
    protected function writeLog($content)
    {
        DebugUtil::writeLog($content, get_called_class());
    }
}
<?php
/**
 * 系统进程处理组件。其作用是进行某一项耗时操作时，先看看当前进程有没有该操作，
 * 可以避免使用冲突
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */



use yii;

class Process extends yii\base\Object
{
    /**
     * 当前进程名字
     * @var string
     */
    public $processName = '';

    /**
     * 返回当前进程是否已经存在或锁定
     * @param string $process 进程名字
     * @return boolean
     */
    public function isLocked()
    {
        return $this->exits($this->processName);
    }

    /**
     * 解除当前进程锁定
     * @return void 
     */
    public function unLock()
    {
        $this->execCommand('rm', $this->processName);
    }

    /**
     * 锁定一个进程
     * @param integer $seconds
     * @return void 
     */
    public function lock($seconds = 0)
    {
        $duration = $seconds < 1 ? 600 : intval($seconds);
        $this->execCommand('set', $this->processName, $duration);
    }

    /**
     * 查找一个进程是否存在
     * @param string $name 进程名字
     * @return boolean
     */
    private function exits($name)
    {
        if (!static::execCommand('get', $name)) {
            $exists = false;
        } else {
            $exists = true;
        }
        return $exists;
    }

    /**
     * 进程处理命令
     * @param string $cmd 命令
     * @param string $name 进程名字
     * @param interger $duration 持续时间
     * @return mixed
     */
    private function execCommand($cmd, $name, $duration = 0)
    {
        $result = '';
        switch ($cmd) {
            case 'set' :
                $result = yii::$app->cache->set('process_lock_'.$name, time(), $duration);
                break;
            case 'get' :
                $result = yii::$app->cache->get('process_lock_'.$name);
                break;
            case 'rm' :
                $result = yii::$app->cache->delete('process_lock_'.$name);
                break;
        }
        return $result;
    }
}
<?php
/**
 * 计划任务处理组件
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */



use Ibos\models\Cron as CronModel;
use yii;

class Cron extends yii\base\Object
{
    /**
     * 系统进程组件
     * @var Process
     */
    private $process;

    /**
     * 开始计划任务处理
     * @param interger $cronId 计划任务id
     * @return boolean 执行成功与否
     */
    public function run($cronId = 0)
    {
        $cron = $this->getCronByCronId($cronId);
        $this->initSystemProcess($cron);
        if ($this->currentProcessExists()) {
            return false;
        } else {
            $this->lockCurrentProcess();
        }
        // 处理当前任务
        if (!empty($cron)) {
            shell_exec($cron['command']);
            $cron->setNextTime();
        }
        // 插入下一次任务记录
        $this->markNextCronTime();
        // 解锁当前进程
        $this->unlockCurrentProcess();
        return true;
    }

    /**
     * 返回当前进程是否存在
     * @return boolean
     */
    protected function currentProcessExists()
    {
        return $this->process->isLocked();
    }

    /**
     * 锁定当前进程
     * @return void 
     */
    protected function lockCurrentProcess()
    {
        $this->process->lock(600);
    }

    /**
     * 解锁当前进程
     * @return void 
     */
    protected function unlockCurrentProcess()
    {
        $this->process->unLock();
    }

    /**
     * 初始化系统进程
     * @param mixed $cron
     * @return void
     */
    protected function initSystemProcess($cron)
    {
        // 进程名
        $processName = 'MAIN_CRON_'.(empty($cron) ? 'CHECKER' : $cron['cron_id']);
        $this->process = new Process(['processName' => $processName]);
        // 如果指定了进程id,先解锁系统进程
        if (!empty($cron)) {
            $this->process->unLock();
        }
    }

    /**
     * 根据定时任务ID返回一条定时任务记录
     * @param integer $cronId
     * @return mixed
     */
    protected function getCronByCronId($cronId)
    {
        if (!empty($cronId)) {
            $cron = CronModel::findOne($cronId);
        } else {
            // 如果没有指定进程id,则查询下一次应该执行的进程
            $cron = CronModel::fetchByNextRuntime(NOW);
        }
        return $cron;
    }

    /**
     * 插入下一次任务记录
     * @return void 
     */
    private function markNextCronTime()
    {
        $cron = CronModel::fetchNextCron();
        if (!empty($cron) && !empty($cron['nextrun'])) {
            $nextTime = $cron['nextrun'];
        } else {
            $nextTime = NOW + 86400 * 365;
        }
        yii::$app->cache->set('cronnextrun', $nextTime);
    }
}
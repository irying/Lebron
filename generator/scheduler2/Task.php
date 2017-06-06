<?php
/**
 * @link http://www.kyrieup.com/
 * @package Task.php
 * @author kyrie
 * @date: 2017/4/6 下午11:11
 */
class Task
{
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $isFirstYield = true;

    public function __construct($taskId, Generator $coroutine)
    {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param $sendValue
     */
    public function setSendValue($sendValue)
    {
        $this->sendValue = $sendValue;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        if ($this->isFirstYield) {
            $this->isFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
//            var_dump($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    /**
     * returns FALSE if the iterator has been closed. Otherwise returns TRUE.
     * @return bool
     */
    public function isFinished()
    {
        return !$this->coroutine->valid();
    }
}
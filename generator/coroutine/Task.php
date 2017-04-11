<?php

class Task {

    /**
     * @var
     */
    protected $taskId;

    /**
     * Generator对象
     * @var Generator
     */
    protected $coroutine;

    /**
     * 发送到Generator中的值
     * @var null
     */
    protected $sendValue = null;

    /**
     * @var bool
     */
    protected $beforeFirstYield = true;     //用于判断是否第一次执行Generator

    public function __construct($taskId, Generator $coroutine) {
        $this->taskId = $taskId;
        $this->coroutine = $coroutine;
    }

    /**
     * @return mixed
     */
    public function getTaskId() {
        return $this->taskId;
    }

    /**
     * @param $sendValue
     */
    public function setSendValue($sendValue) {//指定哪些值将被发送到下次的恢复执行中
        $this->sendValue = $sendValue;
    }

    /**
     * @return mixed
     */
    public function run() {
        if ($this->beforeFirstYield) {//第一次执行，返回当前的value，上文中已解释过这里的原因
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    /**
     * @return bool
     */
    public function isFinished() {
        return !$this->coroutine->valid();
    }
}
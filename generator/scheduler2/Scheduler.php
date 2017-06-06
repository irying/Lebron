<?php

/**
 * @link http://www.kyrieup.com/
 * @package Scheduler.php
 * @author kyrie
 * @date: 2017/4/6 下午11:21
 */
class Scheduler
{
    protected $maxTaskId = 0;
    protected $taskMap = [];
    protected $taskQueue;

    public function __construct()
    {
        $this->taskQueue = new SplQueue();
    }

    public function newTask(Generator $coroutine)
    {
        $tid = ++$this->maxTaskId;
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
    }

    /**
     * @param Task $task
     */
    public function schedule(Task $task)
    {
        $this->taskQueue->enqueue($task);
    }

    public function run()
    {
        while (!$this->taskQueue->isEmpty()) {
            $task = $this->taskQueue->dequeue();
            $retval = $task->run();
//            var_dump($retval);
            if ($retval instanceof SystemCall) {//如果任务发出了一个系统调用，那么执行该系统调用
                $retval($task, $this);
                continue;//因为在系统调用中已经重新调度task，并且此时任务肯定还未执行结束，所以直接continue
            }
            if ($task->isFinished()) {
                unset($this->taskMap[$task->getTaskId()]);
            } else {
                $this->schedule($task);
            }
        }
    }
}
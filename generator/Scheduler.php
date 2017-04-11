<?php
class Scheduler2 {

    /**
     * @var int
     */
    protected $maxTaskId = 0;

    /**
     * taskId => task
     * @var array
     */
    protected $taskMap = [];

    /**
     * @var SplQueue
     */
    protected $taskQueue;

    public function __construct() {
        $this->taskQueue = new SplQueue();
    }

    /**
     * @param Generator $coroutine
     * @return int
     */
    public function newTask(Generator $coroutine) {
        $tid = ++$this->maxTaskId;
        $task = new Task2($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }

    /**
     * @param Task2 $task
     */
    public function schedule(Task2 $task) {
        $this->taskQueue->enqueue($task);
    }

    /**
     *
     */
    public function run() {//多个任务是交替串行执行的
        while (!$this->taskQueue->isEmpty()) {//如果队列任务不为空，则不断出队任务，并执行
            $task = $this->taskQueue->dequeue();
            $retval = $task->run();

            if ($retval instanceof SystemCall) {//如果任务发出了一个系统调用，那么执行该系统调用
                $retval($task, $this);
                continue;//因为在系统调用中已经重新调度task，并且此时任务肯定还未执行结束，所以直接continue
            }

            if ($task->isFinished()) {//判断当前任务是否执行结束了
                unset($this->taskMap[$task->getTaskId()]);
            } else {//还没执行结束的任务需要重新入队，以便下次调度
                $this->schedule($task);
            }
        }
    }
}
<?php
class Scheduler {

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
        $task = new Task($tid, $coroutine);
        $this->taskMap[$tid] = $task;
        $this->schedule($task);
        return $tid;
    }

    /**
     * @param Task $task
     */
    public function schedule(Task $task) {
        $this->taskQueue->enqueue($task);
    }

    /**
     *
     */
    public function run() {//多个任务是交替串行执行的
        while (!$this->taskQueue->isEmpty()) {//如果队列任务不为空，则不断出队任务，并执行
            $task = $this->taskQueue->dequeue();
            $task->run();

            if ($task->isFinished()) {//判断当前任务是否执行结束了
                unset($this->taskMap[$task->getTaskId()]);
            } else {//还没执行结束的任务需要重新入队，以便下次调度
                $this->schedule($task);
            }
        }
    }
}
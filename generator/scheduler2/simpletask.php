<?php
/**
 * @link http://www.kyrieup.com/
 * @package task1.php
 * @author kyrie
 * @date: 2017/4/6 下午11:29
 */
require_once './Scheduler.php';
require_once './Task.php';
require_once './SystemCall.php';

function getTaskId() {
    return new SystemCall(function(Task $task, Scheduler $scheduler) {
        $task->setSendValue($task->getTaskId());//产生任务需要的value，在task->run()时会通过task->coroutine->send(task->sendValue)发送到Generator中
        $scheduler->schedule($task);//重新调度任务
    });
}
function task($max) {
    for ($i=1; $i <= $max; ++$i) {
        $tid = (yield getTaskId());
        echo "this is task $tid and $i".PHP_EOL;
        yield;
    }
}


$scheduler = new Scheduler();
$scheduler->newTask(task(6));
$scheduler->newTask(task(2));
$scheduler->run();
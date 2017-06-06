<?php

require './Scheduler.php';
require './Task.php';
require './SystemCall.php';

function getTaskId() {
    return new SystemCall(function(Task2 $task, Scheduler2 $scheduler) {
        $task->setSendValue($task->getTaskId());//产生任务需要的value，在task->run()时会通过task->coroutine->send(task->sendValue)发送到Generator中
        $scheduler->schedule($task);//重新调度任务
    });
}

function task($max)
{
    $tid = (yield getTaskId()); //发出一个系统调用，这里可以看成：return getTaskId();$tid = task->sendValue;
    for ($i = 1; $i <= $max; ++$i) {
        echo "This is task $tid iteration $i.\n";
        yield;
    }
}

$scheduler = new Scheduler2;

$scheduler->newTask(task(10));
$scheduler->newTask(task(5));

$scheduler->run();
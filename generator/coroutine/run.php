<?php

require './Scheduler.php';
require './Task.php';

function task1()
{//协程1
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i" . PHP_EOL;
        yield;
    }
}

function task2()
{//协程2
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i" . PHP_EOL;
        yield;
    }
}

$scheduler = new Scheduler;

$scheduler->newTask(task1());
$scheduler->newTask(task2());

$scheduler->run();
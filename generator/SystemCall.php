<?php

class SystemCall
{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task2 $task, Scheduler2 $scheduler)
    {//当SystemCall对象被当做函数调用时调用该方法
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}
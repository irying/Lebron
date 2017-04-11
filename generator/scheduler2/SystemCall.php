<?php

/**
 * @link http://www.kyrieup.com/
 * @package SystemCall.php
 * @author kyrie
 * @date: 2017/4/6 下午11:39
 */
class SystemCall
{
    protected $callback;
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task, Scheduler $scheduler)
    {
        $callback = $this->callback;
        return $callback($task, $scheduler);
    }
}
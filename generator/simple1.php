<?php

/**
 * @link http://www.kyrieup.com/
 * @package Generator.php
 * @author kyrie
 * @date: 2017/4/6 下午9:18
 */
//与一般函数的区别在于：
//它不能return $notNULLValue（不能有，会报语法错误= =PHP Fatal error: Generators cannot return values using "return"），
//但可以是return;（相当于return NULL;其实当一个函数没有明确进行return时，PHP会自动为函数加入return;）
//必须含有yield关键字（当生成器执行的时候，每次执行到yield都会中断，并且将$someValue作为返回值，如果有的话;没有则是返回NULL
//class Generator implements Iterator {//实现了Iterator接口，可被foreach迭代访问
//    /* Methods */
//    public mixed current(void)   //获取yielded value
//    public mixed key(void)       //获取yielded key
//    public void next(void)       //从上一次断点之后继续执行
//    public void rewind(void)     //重置迭代
//    public mixed send(mixed $value )          //向生成器中传入$value后，从上一次断点之后继续执行
//    public mixed throw (Exception $exception ) //向生成器中抛入一个异常，从上一次断点之后继续执行
//    public bool valid(void)      //检查迭代是否结束了
//    public void __wakeup(void)   //当序列化Generator对象时抛出异常，即Generator对象不能进行序列化
//}

// 官网的一个例子： http://php.net/manual/zh/generator.send.php
echo memory_get_usage() . "\n"; // 344816
function makeRange($length)
{
    $dataset = [];

    for ($i = 0; $i < $length; $i++)
        $dataset[] = $i;

    return $dataset;
}

$customRange = makeRange(1000000);
foreach ($customRange as $i)
    echo $i, PHP_EOL;
echo memory_get_usage() . "\n"; // 33903536
<?php
/**
 * @link http://www.kyrieup.com/
 * @package send2.php
 * @author kyrie
 * @date: 2017/4/6 下午10:00
 */
// 官网的一个例子： http://php.net/manual/zh/generator.send.php
function nums() {
    for ($i = 0; $i < 5; ++$i) {
        //get a value from the caller
        $cmd = (yield $i);
    }
}

$gen = nums();
foreach($gen as $v)
{
    echo "{$v}\n";
}
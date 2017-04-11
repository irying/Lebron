<?php
/**
 * @link http://www.kyrieup.com/
 * @package before.php
 * @author kyrie
 * @date: 2017/4/6 下午10:19
 */
function gen() {
    $a = 'foo';
    $b = (yield $a);//这里其实可以看做两句语句的执行：return $a; $b = $sendedValue;
    var_dump($b);
    yield 'bar';
}
$gen = gen();
//var_dump($gen->current());
var_dump($gen->send('something'));

//output:
//string(3) "foo"
//string(9) "something"
//string(3) "bar"
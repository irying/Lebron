<?php
/**
 * @link http://www.kyrieup.com/
 * @package send.php
 * @author kyrie
 * @date: 2017/4/6 下午9:59
 */
function nums() {
    for ($i = 0; $i < 5; ++$i) {
        //get a value from the caller
        $cmd = (yield $i);

        if($cmd == 'stop')
            return;//exit the function
    }
}

$gen = nums();
foreach($gen as $v)
{
    if($v == 3)//we are satisfied
        $gen->send('stop');

    echo "{$v}\n";
}
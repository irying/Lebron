<?php
/**
 * @link http://www.kyrieup.com/
 * @package simple2.php
 * @author kyrie
 * @date: 2017/4/6 下午9:30
 */

echo memory_get_usage() . "\n"; // 344080
function makeRange($length)
{
    for ($i = 0; $i < $length; $i++)
        yield $i;
}

foreach (makeRange(1000000) as $i)
{
    echo $i, PHP_EOL;
}
echo memory_get_usage() . "\n"; // 344192
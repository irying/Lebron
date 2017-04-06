<?php
/**
 * @link http://www.kyrieup.com/
 * @package simple2.php
 * @author kyrie
 * @date: 2017/4/6 下午9:30
 */
//在物件導向時代，我們會使用foreach的方式遍訪每一個物件，傳統上我們會採用Iterator Pattern，去實作Iterator interface，
// 簡單地說，generator就是更簡單的iterator。
// Generator讓你不須實踐Iterator interface，也不須將資料放在記憶體中，直到foreach要取值時，才及時產生資料使用yield傳回。
// 與傳統函式最大的差別在於return回傳值後，函式所在的記憶體在stack會釋放，導致函式內的變數都會不見，
// 但generator函式yield回傳generator物件後，generator函式所在的記憶體stack並沒有釋放，所有函式內的變數都被保留。除此之外，當generator函式再次被呼叫時，會從上一次yield的下一行開始執行，而不是從函式的第一行開始執行。
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
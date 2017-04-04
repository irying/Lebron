<?php
/**
 * @link http://www.kyrieup.com/
 * @package multiple.php
 * @author kyrie
 * @date: 2017/4/4 下午6:21
 */
require __DIR__ . '/vendor/autoload.php';
$lastYear = [
    100,
    200,
    300,
    400
];

$thisYear = [
    102,
    160,
    400,
    580,
];

$twoYears = collect($thisYear)->zip($lastYear);
$profit = collect($thisYear)->zip($lastYear)->map(function ($monthly){
   return $monthly->first() - $monthly->last();
});

dd($twoYears, $profit);
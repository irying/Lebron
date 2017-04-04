<?php
/**
 * @link http://www.kyrieup.com/
 * @package map2.php
 * @author kyrie
 * @date: 2017/4/4 下午4:58
 */
require __DIR__ . '/vendor/autoload.php';
$gates = [
    'cool_one_A1',
    'cool_Two_A2',
    'cool_A3',
    'B1',
];

// common way
$board = collect($gates)->map(function ($gate){
    $parts = explode('_', $gate);
    return end($parts);
});

// better way
$boardToo = collect($gates)->map(function ($gate){
    return collect(explode('_', $gate))->last();
});
dump($board, $boardToo);


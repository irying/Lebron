<?php
/**
 * @link http://www.kyrieup.com/
 * @package switch.php
 * @author kyrie
 * @date: 2017/4/4 下午5:30
 */

require __DIR__ . '/vendor/autoload.php';

$events = json_decode(file_get_contents(__DIR__.'/event.json'), true);
$eventsType = collect($events)->pluck('type');
// old way
$score = collect($events)->pluck('type')->map(function ($eventType){
    switch ($eventType){
        case 'PushEvent':
            return 5;
        case 'CreateEvent':
            return 4;
        case 'IssueEvent':
            return 3;
        case 'WatchEvent':
            return 2;
        default:
            return 1;
    }
})->sum();

// fancy way
$scoreToo = collect($events)->pluck('type')->map(function ($eventType){
    return collect([
        'PushEvent' => 5,
        'CreateEvent' => 4,
        'IssueEvent' => 3,
        'WatchEvent' => 2,
    ])->get($eventType, 1);
})->sum();
dd($events, $eventsType, $score, $scoreToo);
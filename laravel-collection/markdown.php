<?php
/**
 * @link http://www.kyrieup.com/
 * @package markdown.php
 * @author kyrie
 * @date: 2017/4/4 下午6:08
 */
require __DIR__ . '/vendor/autoload.php';

$messages = [
    'The introduction to the notification.',
    'Notification Action',
    'Thank you for using our application!'
];
// first way
$comment = '-' . implode("\n-", $messages);

// second way
//$secondComment = '- '.array_shift($messages);
//foreach ($messages as $message) {
//    $secondComment .= "\n-{$message}";
//}

//third way
$thirdComment = collect($messages)->map(function ($message){
    return "- {$message}";
})->implode("\n");
dd($comment, $thirdComment);
<?php
/**
 * DEBUG工具类，主要提供自定义日志记录，以及告警通知等功能。
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */

namespace Ibos\utils;

use yii;
use Exception;

class DebugUtil
{
    /**
     *
     * @var array
     */
    private static $log = [];

    /**
     * write an exception log and notify the administrator
     * @param Exception $exception
     * @return void
     */
    public static function writeExceptionLogAndAlarm(\Exception $exception, $category = 'exception')
    {
        static::writeExceptionLog($exception, $category);
        static::sendBugReportEmailToAdmin(static::getFormatedExceptionLogArray($exception));
    }

    /**
     * write an exception log
     * @param Exception $exception global exception object
     * @return void
     */
    public static function writeExceptionLog(\Exception $exception, $category = 'exception')
    {
        static::writeLog(static::getFormatedExceptionLogArray($exception), $category);
    }

    /**
     * start tracking a request
     * this method should be used in conjunction with traceRequestEnd
     * in the context of a request to invoke,for example：
     * ```
     *  DebugUtil:traceRequestBegin(['token'=>'token','str'=>'str'],'api.wechat.sendmsg');
     *  $result = $curl->fetch(API_ADDRESS);
     *  DebugUtil:traceRequestEnd($result);
     * ```
     * @staticvar array $logs
     * @param array $params request parameters
     * @param string $category log category
     * @return array|void the first call has no return value，
     * the second call returns the previous array
     */
    public static function traceRequestBegin($params = [], $category = '')
    {
        if (!empty(static::$log)) {
            $return = static::$log;
            static::$log = [];
            return $return;
        } else {
            $time = microtime(true);
            static::$log = [
                'begin' => $time,
                'params' => $params,
                'category' => $category,
            ];
        }
    }

    /**
     * track request end。
     * this method should be used in conjunction with traceRequestBegin
     * in the context of a request to invoke,for example:
     * ```
     *  DebugUtil:traceRequestBegin(['token'=>'token','str'=>'str'],'api.wechat.sendmsg');
     *  $result = $curl->fetch(API_ADDRESS);
     *  DebugUtil:traceRequestEnd($result);
     * ```
     * @param mixed $res The request result.
     * @return void
     */
    public static function traceRequestEnd($res)
    {
        $endTime = microtime(true);
        $context = static::traceRequestBegin();
        if (!empty($context)) {
            $trace = [
                'time' => sprintf('%.3f', $endTime - $context['begin']),
                'params' => $context['params'],
                'result' => $res,
            ];
            static::info($trace, $context['category']);
        }
    }

    /**
     * Logging
     * @param string $msg
     * @param string $category
     */
    public static function info($msg, $category = __METHOD__)
    {
        if (YII_DEBUG) {
            static::writeLog($msg, $category);
        }
    }

    /**
     * writes the program custom log
     * @param mixed $msg The information to be recorded, can be either a string or an array
     * @param string $category Log category
     * @return void
     */
    public static function writeLog($msg, $category = __METHOD__)
    {
        if (is_array($msg)) {
            $msg['category'] = $category;
        } else {
            $msg = [
                'text' => $msg,
                'category' => $category
            ];
        }
        yii::info($msg, 'workerLog');
    }

    /**
     * returns an array of ExceptionLogs after formatting
     * @param Exception $exception
     * @return array
     */
    protected static function getFormatedExceptionLogArray(\Exception $exception)
    {
        $message = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ];
        return $message;
    }

    /**
     * mail alerts to the administrator
     * @param array $logData log array
     * @return integer number of messages successfully sent
     */
    protected static function sendBugReportEmailToAdmin($logData)
    {
        $subject = 'AN ERROR OCCUR';
        $toEmail = yii::$app->params['debug']['notifyEmails'];
        // temporarily handle the warning switch.
        if (file_exists('alarm.lock')) {
            return;
        }
        if (is_array($toEmail)) {
            $messages = [];
            foreach ($toEmail as $email) {
                $messages[] = yii::$app->mailer->compose('error', $logData)
                    ->setTo($email)
                    ->setFrom(['no-reply@notice.ibos.cn' => 'BUG'])
                    ->setSubject($subject);
            }
            return yii::$app->mailer->sendMultiple($messages);
        } else {
            return yii::$app->mailer->compose('error', $logData)
                    ->setTo($toEmail)
                    ->setFrom(['no-reply@notice.ibos.cn' => 'BUG'])
                    ->setSubject($subject)
                    ->send();
        }
    }
}
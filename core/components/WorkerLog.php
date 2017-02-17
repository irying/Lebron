<?php
/**
 * 重写LEVEL等于info的日志接口，主要是剔除了一些不需要的数据字段，以及存储格式变成JSON
 * 主要配合DebugUtil使用。
 *
 * @link http://www.ibos.com.cn/
 * @copyright Copyright (c) 2016 IBOS Inc
 */



use Ibos\utils\AliyunLog\AliyunLogApi;
use Ibos\utils\AliyunLog\AliyunLogClientFactory;
use yii;
use yii\helpers\VarDumper;
use yii\log\Target;


class WorkerLog extends Target
{
    
    /**
     * Stores log messages to Aliyun SLS service.
     */
    public function export()
    {
        $logContentArr = [];
        foreach ($this->messages as $message) {
            list($text, , $origCategory, $timestamp) = $message;
            if ($origCategory == 'application') {
                continue;
            }
            if (isset($text['context'])) {
                $text['context'] = $this->getContextMessage();
            }
            $category = $text['category'];
            unset($text['category']);
            $content = VarDumper::export($text);
            $data = [
                'category' => $category,
                'logtime' => date('Y-m-d H:i:s', $timestamp),
                'message' => $content,
            ];
            $logContentArr[] = $data;
        }
        if (!empty($logContentArr)) {
            $slsConf = AliyunLogClientFactory::getConfig();
            AliyunLogApi::getInstance($slsConf['project'])->writeLogs($slsConf['logstore'], 'worker', null,
                $logContentArr);
        }
    }
}
<?php


namespace features\traits\action;

trait CustomRunAction
{
    /**
     * 运行匹配正则表达式的方法
     *
     * @param string $pattern 正则表达式，用于匹配方法名
     * @return bool
     */
    private function runSuitedMethod($pattern)
    {
        $methodNameArr = get_class_methods($this);

        $runStatus = true;
        foreach ($methodNameArr as $methodName) {
            if (preg_match($pattern, $methodName) > 0) {
                if (in_array(strtolower($methodName), ['beforerun', 'afterrun'])) {
                    continue;
                }
                $res = call_user_func([$this, $methodName]);
                $runStatus = $runStatus && $res;
            }
        }

        return $runStatus;
    }

    protected function beforeRun()
    {
        // 运行以 beforeRun（不区分大小写） 结尾的方法
        return $this->runSuitedMethod('/beforeRun$/i');
    }

    protected function afterRun()
    {
        // 运行以 afterRun（不区分大小写）结尾的方法
        $this->runSuitedMethod('/afterRun$/i');
    }
}
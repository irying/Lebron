<?php





class Request extends \yii\web\Request
{
    /**
     * 尝试从 $_GET 和 $_POST 中取得数据
     * 
     * @param string $name
     * @param null $defaultValue
     * @return mixed
     */
    public function getRequest($name, $defaultValue = null)
    {
        $value = $this->get($name);

        if (empty($value)) {
            $value = $this->post($name);
        }

        if (empty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }
}
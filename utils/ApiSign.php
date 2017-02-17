<?php


class ApiSign
{
    private static $sign_salt = 'your sign salt';

    final public static function checkSign()
    {
        $get = filter_input_array(INPUT_GET);
        if (filter_input_array(INPUT_POST)) {
            $get = array_merge($get, filter_input_array(INPUT_POST));
        }

        $str = '';
        krsort($get);
        foreach ($get as $k => $v) {
            if ($k != 'tk') {
                $str .= $v;
            }
        }
        $tk = $get['tk'];
        if ($tk != md5(md5($str.self::$sign_salt))) {
            return false;
        }

        return true;
    }

    final public static function createSign($arr = array())
    {
        if (empty($arr)) {
            return '';
        }
        krsort($arr);
        $str = '';
        foreach ($arr as $v) {
            $str .= $v;
        }
        $tk = md5(md5($str.self::$sign_salt));

        return $tk;
    }
}

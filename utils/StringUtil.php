<?php

/**
 * 字符串工具类
 *
 * @author banyanCheung <banyan@ibos.com.cn>
 * @link http://www.ibos.com.cn/
 * @copyright Copyright &copy; 2015 IBOS Inc
 */

use yii;

class StringUtil extends yii\helpers\StringHelper
{

    /**
     * 检测一个字符串是否email格式
     * @param string $email
     * @return boolean
     */
    public static function isEmail($email)
    {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
    }

    /**
     * 检测一个字符串是否手机格式
     * @param string $str
     * @return boolean
     */
    public static function isMobile($str)
    {
        return preg_match("/^1\d{10}$/", $str);
    }

    /**
     * 检查字符串是否guid
     * @param type $string
     */
    public static function isGuid($string)
    {
        $reg = "/^[0-9a-fA-F]{8}(-[0-9a-fA-F]{4}){3}-[0-9a-fA-F]{12}$/";
        return preg_match($reg, $string) ? true : false;
    }

    /**
     * 检查字符串是否url
     * @param type $string
     * @return boolean
     */
    public static function isUrl($string)
    {
        return preg_match('/^http[s]?:\/\/' .
            '(([0-9]{1,3}\.){3}[0-9]{1,3}' . // IP形式的URL- 199.194.52.184
            '|' . // 允许IP和DOMAIN（域名）
            '([0-9a-z_!~*\'()-]+\.)*' . // 域名- www.
            '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.' . // 二级域名
            '[a-z]{2,6})' . // first level domain- .com or .museum
            '(:[0-9]{1,4})?' . // 端口- :80
            '((\/\?)|' . // a slash isn't required if there is no file name
            '(\/[0-9a-zA-Z_!~\'\(\)\[\]\.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/', $string) ? true : false;
    }

    /**
     * 对字符串进行hash，一般用于密码验证与生成
     * @param string $method hash的方法
     * @param string $string hash的对象
     * @param string $key hash的私钥
     * @return string hash之后的字符串
     */
    public static function hash($method, $string, $key)
    {
        return $method($string . $key);
    }

    public static function hash2($method, $params, $separator = '')
    {
        return $method(implode($separator, $params));
    }

    /**
     * 查找是否包含在内,两边都可以是英文逗号相连的字符串
     * @param string $string 目标范围
     * @param string $id 所有值
     * @return boolean
     */
    public static function findIn($string, $id)
    {
        $string = trim($string, ",");
        $newId = trim($id, ",");
        if ($newId == '' || $newId == ',') {
            return false;
        }
        $idArr = explode(",", $newId);
        $strArr = explode(",", $string);
        if (array_intersect($strArr, $idArr)) {
            return true;
        }
        return false;
    }

    /**
     * 用于过滤标签，输出没有html的干净的文本
     * @param string text 文本内容
     * @return string 处理后内容
     */
    public static function filterCleanHtml($text)
    {
        $text = nl2br($text);
        $text = static::realStripTags($text);
        $text = addslashes($text);
        $text = trim($text);
        return $text;
    }

    /**
     * 获取随机字符串
     * @param integer $length 要多少位
     * @param integer $numeric 是否只要数字
     * @return string 随机产生的字符串
     */
    public static function getRandom($length, $numeric = 0)
    {
        $seed = base_convert(md5(microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($index = 0; $index < $length; $index++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * 生成GUID
     * @return string
     */
    public static function createGuid()
    {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = chr(45); // "-"     
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }

    /**
     * 获取文件扩展名
     * @param $fileName 文件名
     * @since IBOS1.0
     */
    public static function getFileExt($fileName)
    {
        return addslashes(strtolower(substr(strrchr($fileName, '.'), 1, 10))) . '';
    }

    /**
     * 处理sql语句
     * @param string $sql 原始的sql
     * @return array
     */
    public static function splitSql($sql)
    {
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesArr = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesArr as $querys) {
            $queries = explode("\n", trim($querys));
            foreach ($queries as $query) {
                $val = substr(trim($query), 0, 1) == "#" ? null : $query;
                if (isset($ret[$num])) {
                    $ret[$num] .= $val;
                } else {
                    $ret[$num] = $val;
                }
            }
            $num++;
        }
        return $ret;
    }

    /**
     * 对文本加强处理过滤标签
     * @param string $str 要处理的文本
     * @param string $allowableTags 允许的标签
     * @return string 处理后的文本
     */
    protected static function realStripTags($str, $allowableTags = "")
    {
        return strip_tags(stripslashes(htmlspecialchars_decode($str)), $allowableTags);
    }

    /**
     * 过滤字符串
     * @param string $string 要过滤的字符串
     * @param string $delimiter 分割符
     * @param bool $unique 是否过滤重复值
     * @return string 过滤后的字符串
     */
    public static function filterStr($string, $delimiter = ',', $unique = true)
    {
        $filterArr = array();
        $strArr = explode($delimiter, $string);
        foreach ($strArr as $str) {
            if (!empty($str)) {
                $filterArr[] = trim($str);
            }
        }
        return implode($delimiter, $unique ? array_unique($filterArr) : $filterArr);
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    public static function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * 返回拼音首字母
     * @param string $string
     * @return string
     */
    public static function getLetter($string)
    {
        $py = StringUtil::getPY($string, true);
        return strtoupper($py{0});
    }

    /**
     * 获取$string的拼音
     * @param type $string 词条字符串
     * @param type $first 是否只取首字
     * @return string
     */
    public static function getPY($string, $first = false, $phonetic = false)
    {
        $pdat = 'data/pydata/py.dat'; // 多音字dat数据
        $fp = fopen($pdat, 'rb');
        if (!$fp) {
            return '*';
        }
        $in_code = strtoupper(CHARSET);
        $out_code = 'GBK';
        $strlen = mb_strlen($string, $in_code);
        $ret = '';
        for ($i = 0; $i < $strlen; $i++) {
            $py = '';
            $izh = mb_substr($string, $i, 1, $in_code);
            if (preg_match('/^[a-zA-Z0-9]$/', $izh)) {
                $ret .= $izh;
            } elseif (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $izh)) { // 只取纯汉字，其他非汉字符号一概忽略。
                $char = iconv($in_code, $out_code, $izh);
                $high = ord($char[0]) - 0x81;
                $low = ord($char[1]) - 0x40;
                $offset = ($high << 8) + $low - ($high * 0x40);
                if ($offset >= 0) {
                    fseek($fp, $offset * 8, SEEK_SET);
                    $p_arr = unpack('a8py', fread($fp, 8));
                    $py = isset($p_arr['py']) ? ($phonetic ? $p_arr['py'] : substr($p_arr['py'], 0, -1)) : '';
                    $ret .= $first ? $py[0] : '' . $py;
                }
            }
        }
        fclose($fp);
        return $ret;
    }

    /**
     * 获取分与不分前缀的id
     * @param mixed $ids
     * @param boolean $index 按前缀索引
     * @return array
     */
    public static function getId($ids, $index = false)
    {
        $newIds = array();
        $idList = is_array($ids) ? $ids : explode(',', $ids);
        foreach ($idList as $idstr) {
            if (!empty($idstr)) {
                if ($index) {
                    $prefix = substr($idstr, 0, 1);
                    $newIds[$prefix][] = substr($idstr, 2);
                } else {
                    $newIds[] = substr($idstr, 2);
                }
            }
        }
        return $newIds;
    }

    /**
     * 把手机号字符串转换为可显示的形式
     * @param string $mobile
     * @return string
     */
    public static function mobileMask($mobile)
    {
        return !empty($mobile) ? substr($mobile, 0, 3) . '******' . substr($mobile, -2) : '';
    }

    /**
     * 把真实姓名字符串转换为可显示的形式
     * @param string $realname
     * @return string
     */
    public static function realnameMask($realname)
    {
        return !empty($realname) ? mb_substr($realname, 0, 1, 'utf-8') . '**' : '';
    }

    /**
     * 把公司名字字符串转换为可显示的形式
     * @param string $corpname
     * @return string
     */
    public static function corpnameMask($corpname)
    {
        return !empty($corpname) ? mb_substr($corpname, 0, 2, 'utf-8') . '******' . mb_substr($corpname, -2, 2, 'utf-8') : '';
    }

    /**
     * 加密字符串
     * @param type $string
     * @param type $key
     * @return type
     */
    public static function encrypt($string, $key)
    {
        $key = md5($key);
        $x = 0;
        $len = strlen($string);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($string{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密字符串
     * @param type $string
     * @param type $key
     * @return type
     */
    public static function decrypt($string, $key)
    {
        $key = md5($key);
        $x = 0;
        $string = base64_decode($string);
        $len = strlen($string);
        $l = strlen($key);
        $char = '';
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($string, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($string, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($string, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 移除字符串的一部分
     * @param string $piece
     * @param string $string
     * @param string $glue
     * @return mixed
     */
    public static function removePartOfString($piece, $string, $glue = ',')
    {
        $pieces = explode($glue, $string);
        $key = array_search($piece, $pieces);
        unset($pieces[$key]);

        return implode($glue, $pieces);
    }

    /**
     * 获取邮箱的域名
     * @param string $email
     * @return mixed
     */
    public static function getDomainNameOfEmail($email)
    {
       return substr(strrchr($email, "@"), 1);
    }

}

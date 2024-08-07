<?php
// +----------------------------------------------------------------------
// | 通用类包
// +----------------------------------------------------------------------
// | Copyright (c) 2020 https://qilindao.github.io/docs/ All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: joyecZhang <zhangwei762@163.com>
// +----------------------------------------------------------------------

declare (strict_types=1);

namespace LanDao\LaravelCore\Helpers;

/**
 * 字符串操作
 * Class StrHelper
 * @package LanDao\LaravelCore\Helpers
 */
class StrHelper
{
    const UTF8 = 'utf-8';
    const GBK = 'gbk';


    /**
     *  重写ip2long，将ip地址转换为整型
     * @param string $ip
     * @return string
     */
    static function ip2long($ip = '127.0.0.1')
    {
        //ip2long可转换为整型，但会出现携带符号问题。需格式化为无符号的整型，利用sprintf函数格式化字符串。
        //然后用long2ip将整型转回IP字符串
        //MySQL函数转换(无符号整型，UNSIGNED)
        //INET_ATON('218.5.49.94');将IP转为整型 INET_NTOA(3657773406);将整型转为IP
        return sprintf('%u', ip2long($ip));
    }

    /**
     * 截取字符串,支持字符编码,默认为utf-8
     * @param string $string 要截取的字符串编码
     * @param int $start 开始截取
     * @param int $length 截取的长度
     * @param string $charset 原妈编码,默认为UTF8
     * @param bool $dot 是否显示省略号,默认为false
     * @return false|string 截取后的字串
     */
    public static function substr(string $string, int $start, int $length, $charset = self::UTF8, $dot = false)
    {
        switch (strtolower($charset)) {
            case self::GBK:
                $string = self::substrForGbk($string, $start, $length, $dot);
                break;
            case self::UTF8:
                $string = self::substrForUtf8($string, $start, $length, $dot);
                break;
            default:
                $string = substr($string, $start, $length);
        }
        return $string;
    }

    /**
     * 以utf8格式截取的字符串编码
     * @param string $string 要截取的字符串编码
     * @param int $start 开始截取
     * @param null $length 截取的长度，默认为null，取字符串的全长
     * @param bool $dot 是否显示省略号，默认为false
     * @return false|string
     */
    public static function substrForUtf8(string $string, int $start, $length = null, $dot = false)
    {
        $l = strlen($string);
        $p = $s = 0;
        if (0 !== $start) {
            while ($start-- && $p < $l) {
                $c = $string[$p];
                if ($c < "\xC0")
                    $p++;
                elseif ($c < "\xE0")
                    $p += 2;
                elseif ($c < "\xF0")
                    $p += 3;
                elseif ($c < "\xF8")
                    $p += 4;
                elseif ($c < "\xFC")
                    $p += 5;
                else
                    $p += 6;
            }
            $s = $p;
        }

        if (empty($length)) {
            $t = substr($string, $s);
        } else {
            $i = $length;
            while ($i-- && $p < $l) {
                $c = $string[$p];
                if ($c < "\xC0")
                    $p++;
                elseif ($c < "\xE0")
                    $p += 2;
                elseif ($c < "\xF0")
                    $p += 3;
                elseif ($c < "\xF8")
                    $p += 4;
                elseif ($c < "\xFC")
                    $p += 5;
                else
                    $p += 6;
            }
            $t = substr($string, $s, $p - $s);
        }

        $dot && ($p < $l) && $t .= "...";
        return $t;
    }

    /**
     * 以gbk格式截取的字符串编码
     * @param string $string 要截取的字符串编码
     * @param int $start 开始截取
     * @param null $length 截取的长度，默认为null，取字符串的全长
     * @param bool $dot 是否显示省略号，默认为false
     * @return false|string
     */
    public static function substrForGbk(string $string, int $start, $length = null, $dot = false)
    {
        $l = strlen($string);
        $p = $s = 0;
        if (0 !== $start) {
            while ($start-- && $p < $l) {
                if ($string[$p] > "\x80")
                    $p += 2;
                else
                    $p++;
            }
            $s = $p;
        }

        if (empty($length)) {
            $t = substr($string, $s);
        } else {
            $i = $length;
            while ($i-- && $p < $l) {
                if ($string[$p] > "\x80")
                    $p += 2;
                else
                    $p++;
            }
            $t = substr($string, $s, $p - $s);
        }

        $dot && ($p < $l) && $t .= "...";
        return $t;
    }

    /**
     * 以utf8求取字符串长度
     * @param string $string 要计算的字符串编码
     * @return int
     */
    public static function strLenForUtf8(string $string)
    {
        $l = strlen($string);
        $p = $c = 0;
        while ($p < $l) {
            $a = $string[$p];
            if ($a < "\xC0")
                $p++;
            elseif ($a < "\xE0")
                $p += 2;
            elseif ($a < "\xF0")
                $p += 3;
            elseif ($a < "\xF8")
                $p += 4;
            elseif ($a < "\xFC")
                $p += 5;
            else
                $p += 6;
            $c++;
        }
        return $c;
    }

    /**
     * 以gbk求取字符串长度
     * @param string $string 要计算的字符串编码
     * @return int
     */
    public static function strLenForGbk(string $string)
    {
        $l = strlen($string);
        $p = $c = 0;
        while ($p < $l) {
            if ($string[$p] > "\x80")
                $p += 2;
            else
                $p++;
            $c++;
        }
        return $c;
    }

    /**
     * 生成13位码
     * @return string
     */
    public static function getVoucherCode()
    {
        $time = time();
        $start = mt_rand(10000, 99999);
        $end = mt_rand(10000, 99999);
        return $start . substr($time, strlen($time) - 3, 3) . $end;
    }

    /**
     * 生成订单号
     * @param string $prefix 前缀
     * @return string
     */
    public static function orderNo(string $prefix = '')
    {
        return $prefix . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 生成24位唯一订单号码，格式：YYYY-MMDD-HHII-SS-NNNN,NNNN-CC
     *
     * 处理微妙级单号不重复
     *
     * 其中：YYYY=年份，MM=月份，DD=日期，HH=24格式小时，II=分，SS=秒，NNNNNNNN=随机数，CC=检测码
     */
    public static function buildOrderNo()
    {
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $order_id_main = StrHelper . phpdate('YmdHis') . rand(10000000, 99999999);
        //订单号码主体长度
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for ($i = 0; $i < $order_id_len; $i++) {
            $order_id_sum += (int)(substr($order_id_main, $i, 1));
        }
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        return $order_id_main . str_pad((string)((100 - $order_id_sum % 100) % 100), 2, '0', STR_PAD_LEFT);
    }

    /**
     * 字符串匹配替换
     *
     * @param string $search 查找的字符串
     * @param string $replace 替换的字符串
     * @param string $subject 字符串
     * @param null $count
     * @return mixed
     */
    public static function replace(string $search, string $replace, string $subject, &$count = null)
    {
        return str_replace($search, $replace, $subject, $count);
    }

    /**
     * 指定替换最后出现的字符串
     *
     * 例如:<a href="/manage/system/modulelist.html">系统</a><span lay-separator="">&gt;</span><a href="/manage/system/modulelist.html">模块列表</a><span lay-separator="">&gt;</span><a href="/manage/system/editmodule.html">修改模块</a><span lay-separator="">&gt;</span>
     *
     * StrHelper::lreplace('<span lay-separator="">&gt;</span>','',$str)
     *
     * @param $search
     * @param $replace
     * @param $subject
     * @return string
     */
    public static function lreplace($search, $replace, $subject): string
    {
        $pos = strrpos($subject, $search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        return trim($subject);
    }


    /**
     * 将一个字符串部分字符用*替代隐藏
     * @param string $string 待转换的字符串
     * @param int $begin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string $glue 分割符
     * @return bool|string
     */
    public static function hideStr(string $string, int $begin = 0, int $len = 4, int $type = 0, string $glue = "@")
    {
        if (empty($string)) {
            return false;
        }

        $array = [];
        if ($type == 0 || $type == 1 || $type == 4) {
            $strLen = $length = mb_strlen($string);

            while ($strLen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strLen, "utf8");
                $strLen = mb_strlen($string);
            }
        }

        switch ($type) {
            case 0 :
                for ($i = $begin; $i < ($begin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", $array);
                break;
            case 1 :
                $array = array_reverse($array);
                for ($i = $begin; $i < ($begin + $len); $i++) {
                    isset($array[$i]) && $array[$i] = "*";
                }

                $string = implode("", array_reverse($array));
                break;
            case 2 :
                $array = explode($glue, $string);
                $array[0] = self::hideStr($array[0], $begin, $len, 1);
                $string = implode($glue, $array);
                break;
            case 3 :
                $array = explode($glue, $string);
                $array[1] = self::hideStr($array[1], $begin, $len, 0);
                $string = implode($glue, $array);
                break;
            case 4 :
                $left = $begin;
                $right = $len;
                $tem = array();
                for ($i = 0; $i < ($length - $right); $i++) {
                    if (isset($array[$i])) {
                        $tem[] = $i >= $left ? "*" : $array[$i];
                    }
                }

                $array = array_chunk(array_reverse($array), $right);
                $array = array_reverse($array[0]);
                for ($i = 0; $i < $right; $i++) {
                    $tem[] = $array[$i];
                }
                $string = implode("", $tem);
                break;
        }

        return $string;
    }

    /**
     * 判断字符串是否是json格式
     * @param string $str 字符串
     * @param bool $assoc 是否返回关联数组，默认返回对象
     * @return bool|string
     */
    public static function isJson(string $str = '', $assoc = false)
    {
        $data = json_decode($str, $assoc);
        if (($data && is_object($data)) || is_array($data)) {
            return true;
        }
        return false;
    }

    /**
     * 字符串"true"/"false"转成boolean布尔型
     * @param $val
     * @param bool $resultNull
     * @return bool|mixed|null
     */
    public static function isTrue($val, $resultNull = false)
    {
        $boolVal = (is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool)$val);
        return ($boolVal === null && !$resultNull ? false : $boolVal);
    }
}

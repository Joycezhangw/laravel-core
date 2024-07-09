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
 * 安全过滤类库
 * Class SecurityHelper
 * @package LanDao\LaravelCore\Helpers
 */
class SecurityHelper
{
    /**
     * 转义输出字符串
     * @param string $str 被转义的字符串
     * @param string $charset
     * @return string
     */
    public static function escapeHTML($str, $charset = 'ISO-8859-1')
    {
        if (!is_string($str)) return $str;
        return htmlspecialchars($str, ENT_QUOTES, $charset);
    }

    public static function decodeHTML($str)
    {
        if (!is_string($str)) return $str;
        return htmlspecialchars_decode($str);
    }

    /**
     * 转义字符串
     *
     * @param array $array 被转移的数组
     * @return array
     */
    public static function escapeArrayHTML($array)
    {
        if (!is_array($array)) return self::escapeHTML($array);
        $_tmp = array();
        foreach ($array as $key => $value) {
            is_string($key) && $key = self::escapeHTML($key);
            $_tmp[$key] = is_array($value) ? self::escapeArrayHTML($value) : self::escapeHTML($value);
        }
        return $_tmp;
    }
}
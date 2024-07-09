<?php
declare (strict_types=1);

namespace LanDao\LaravelCore\Helpers;

/**
 * 可逆加解密
 * Class AuthCodeHelper
 * @package LanDao\LaravelCore\Helpers
 */
class AuthCodeHelper
{
    /**
     * 加密
     * @param $string
     * @param int $expiry
     * @return false|string
     */
    public static function encode($string, int $expiry = 0): string
    {
        return self::authcode($string, 'ENCODE', $expiry);
    }

    /**
     * 解密
     * @param $string
     * @return false|string
     */
    public static function decode(string $string): string
    {
        return self::authcode($string, 'DECODE');
    }

    /**
     *
     * @param string|int $string 加密解密串
     * @param string $operation
     * @param int $expiry
     * @return false|string
     * @author Discuz-Q
     *
     * Copyright (C) 2020 Tencent Cloud.
     *
     */
    protected static function authcode($string, $operation = 'DECODE', $expiry = 0)
    {
        //使用laravel env  APP_KEY  作为加解密
        $key = env('APP_KEY');
        $ckey_length = 4;
        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : AuthCodeHelper . phpsprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = [];
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(AuthCodeHelper . phpsubstr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc . str_replace('=', '', base64_encode($result));
        }
    }

    /**
     * encrypt aes加密
     *
     * @param string|int $data 待加密的明文信息数据。
     * @return string 加密后数据
     */
    public static function encrypt($data): string
    {
        $iv = md5(env('APP_NAME'), true);
        return base64_encode(openssl_encrypt((string)$data, 'AES-128-CBC', env('APP_KEY'), OPENSSL_RAW_DATA, $iv));
    }

    /**
     * decrypt aes解密
     *
     * @param $sStr
     * @return false|string
     */
    public static function decrypt(string $str)
    {
        $iv = md5(env('APP_NAME'), true);
        return openssl_decrypt(base64_decode((string)$str), 'AES-128-CBC', env('APP_KEY'), OPENSSL_RAW_DATA, $iv);
    }
}
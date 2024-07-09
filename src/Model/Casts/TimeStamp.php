<?php


namespace LanDao\LaravelCore\Model\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 时间转换
 * Class TimeStamp
 * @package JoyceZ\LaravelLib\Model\Casts
 */
class TimeStamp implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return date("Y-m-d H:i:s", $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return strtotime($value);
    }
}
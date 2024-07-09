<?php


namespace LanDao\LaravelCore\Model\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * 日期转换
 * Class DateStamp
 * @package JoyceZ\LaravelLib\Model\Casts
 */
class DateStamp implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return date("Y-m-d", $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return strtotime($value);
    }
}
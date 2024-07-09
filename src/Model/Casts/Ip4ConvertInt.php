<?php


namespace LanDao\LaravelCore\Model\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use LanDao\LaravelCore\Helpers\StrHelper;

/**
 * ip4地址转int
 * Class Ip4ConvertInt
 * @package JoyceZ\LaravelLib\Model\Casts
 */
class Ip4ConvertInt implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return StrHelper::ip2long($value);
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value > 0 ? long2ip(intval($value)) : '-';
    }
}
<?php

namespace LanDao\LaravelCore\Model;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

abstract class BaseModel extends Model
{
    /**
     * 重写日期序列化
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(Carbon::parse($date)->toDateTimeString());
    }
}

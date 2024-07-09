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

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * 日期常用助手函数
 * Class TimeHelper
 * @package LanDao\LaravelCore\Helpers
 */
class TimeHelper
{
    /**
     * 获取今日开始时间戳和结束时间戳
     *
     * 语法：mktime(hour,minute,second,month,day,year) => (小时,分钟,秒,月份,天,年)
     */
    public static function today()
    {
        list($y, $m, $d) = explode('-', date('Y-m-d'));
        return [
            mktime(0, 0, 0, $m, $d, $y),
            mktime(23, 59, 59, $m, $d, $y)
        ];
    }

    /**
     * 昨日
     *
     * @return array
     */
    public static function yesterday()
    {
        $yesterday = date('d') - 1;
        return [
            mktime(0, 0, 0, date('m'), $yesterday, date('Y')),
            mktime(23, 59, 59, date('m'), $yesterday, date('Y'))
        ];
    }

    /**
     * 本周
     * @return array
     */
    public static function week()
    {
        list($y, $m, $d, $w) = explode('-', date('Y-m-d-w'));
        if ($w == 0) $w = 7;
        return [
            mktime(0, 0, 0, $m, $d - $w + 1, $y),
            mktime(23, 59, 59, $m, $d - $w + 7, $y)
        ];
    }

    /**
     * 上周
     *
     * @return array
     */
    public static function lastWeek()
    {
        $timestamp = time();
        return [
            strtotime(date('Y-m-d', strtotime("last week Monday", $timestamp))),
            strtotime(date('Y-m-d', strtotime("last week Sunday", $timestamp))) + 24 * 3600 - 1
        ];
    }

    /**
     * 本月
     *
     * @return array
     */
    public static function thisMonth()
    {
        list($y, $m, $t) = explode('-', date('Y-m-t'));
        return [
            mktime(0, 0, 0, $m, 1, $y),
            mktime(23, 59, 59, $m, $t, $y)
        ];
    }

    /**
     * 上个月
     *
     * @return array
     */
    public static function lastMonth()
    {
        $y = date('Y');
        $m = date('m');
        $begin = mktime(0, 0, 0, $m - 1, 1, (int)$y);
        $end = mktime(23, 59, 59, $m - 1, (int)date('t', $begin), (int)$y);

        return [$begin, $end];
    }

    /**
     * 几个月前
     *
     * @param integer $month 月份
     * @return array
     */
    public static function monthsAgo($month)
    {
        $year = (int)date('Y');
        $start = mktime(0, 0, 0, (int)date('m') - $month, 1, $year);
        $end = mktime(23, 59, 59, (int)date('m') - $month, (int)date('t', $start), $year);
        if (date('m', $start) != date('m', $end)) {
            $end -= 60 * 60 * 24;
        }
        return [
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 返回今年开始和结束的时间戳
     *
     * @return array
     */
    public static function year()
    {
        $y = date('Y');
        return [
            mktime(0, 0, 0, 1, 1, (int)$y),
            mktime(23, 59, 59, 12, 31, (int)$y)
        ];
    }

    /**
     * 返回去年开始和结束的时间戳
     *
     * @return array
     */
    public static function lastYear()
    {
        $year = date('Y') - 1;
        return [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(23, 59, 59, 12, 31, $year)
        ];
    }

    /**
     * 获取几天前零点到现在/昨日结束的时间戳
     *
     * @param int $day 天数
     * @param bool $now 返回现在或者昨天结束时间戳
     * @return array
     */
    public static function dayToNow($day = 1, $now = true)
    {
        $end = time();
        if (!$now) {
            list($foo, $end) = self::yesterday();
        }
        return [
            mktime(0, 0, 0, (int)date('m'), date('d') - $day, (int)date('Y')),
            $end
        ];
    }

    /**
     * 返回几天前的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAgo($day = 1)
    {
        $nowTime = time();
        return $nowTime - self::daysToSecond($day);
    }

    /**
     * 返回几天后的时间戳
     *
     * @param int $day
     * @return int
     */
    public static function daysAfter($day = 1, $nowTime = 0)
    {
        $nowTime = $nowTime ?: time();
        return $nowTime + self::daysToSecond($day);
    }

    /**
     * 天数转换成秒数
     *
     * @param int $day
     * @return int
     */
    public static function daysToSecond($day = 1)
    {
        return $day * 86400;
    }

    /**
     * 周数转换成秒数
     *
     * @param int $week
     * @return int
     */
    public static function weekToSecond($week = 1)
    {
        return self::daysToSecond() * 7 * $week;
    }

    /**
     * 某年
     * @param $year
     * @return array
     */
    public static function someYear($year)
    {
        $start_month = 1;
        $end_month = 12;

        $start_time = $year . '-' . $start_month . '-1 00:00:00';
        $end_month = $year . '-' . $end_month . '-1 23:59:59';
        $end_time = date('Y-m-t H:i:s', strtotime($end_month));

        return [
            strtotime($start_time),
            strtotime($end_time)
        ];
    }

    /**
     * 某月
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public static function aMonth($year = 0, $month = 0)
    {
        $year = $year == 0 ? (int)date('Y') : $year;
        $month = $month == 0 ? (int)date('m') : $month;
        $day = date('t', strtotime($year . '-' . $month));
        return [
            strtotime($year . '-' . $month),
            mktime(23, 59, 59, $month, (int)$day, (int)$year)
        ];
    }

    /**
     * 根据日期获取是星期几
     * @param int $time
     * @param string $format
     * @return mixed
     */
    public static function getWeekName(int $time, $format = "周")
    {
        $week = date('w', $time);
        $weekName = ['日', '一', '二', '三', '四', '五', '六'];
        foreach ($weekName as &$item) {
            $item = $format . $item;
        }
        return $weekName[$week];
    }

    /**
     * 获取指定开始日期到结束日期
     * @param int $start_day 开始天数，以当天开始：0，每增加一天，在当天基础上 +1
     * @param int $end_day
     * @return array
     */
    public static function getFutureHowManyDays(int $start_day = 0, int $end_day = 7)
    {
        $dateArr = [];
        for ($i = $start_day; $i < $end_day; $i++) {
            $dateArr[$i] = date('Y-m-d', strtotime(date('Y-m-d') . '+' . $i . 'day'));
        }
        return $dateArr;
    }

    /**
     * 根据开始/结束时间，并以分钟为分界点生成 时间范围
     * @param string $start_time
     * @param string $end_time
     * @param int $minute
     * @return array
     */
    public static function buildEveryDayTimeRange(string $start_time = '09:00', string $end_time = '22:30', int $minute = 30)
    {
        $arr = [];
        for ($i = strtotime($start_time); $i <= strtotime($end_time); $i = $i + 60 * $minute) {
            $arr[] = date("H:i", $i);
        }
        $result = [];
        $num = count($arr);
        foreach ($arr as $key => $val) {
            if ($key < $num) {
                $time_str = strtotime($val);
                $result[$key + 1] = $val . '-' . date('H:i', $time_str + 60 * $minute);
            }
        }
        return $result;
    }

    /**
     * 格式化时间戳
     *
     * @param $time
     * @return string
     */
    public static function formatTimestamp($time)
    {
        $min = $time / 60;
        $hours = $time / 3600;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));

        return $days . " 天 " . $hours . " 小时 " . $min . " 分钟 ";
    }

    /**
     * 格式化时间戳
     * @param int $time 时间戳
     * @param string $format 格式化时间格式
     * @return false|string
     */
    public static function formatParseTime(int $time = 0, string $format = 'Y-m-d H:i:s')
    {
        return $time > 0 ? date($format, $time) : '';
    }

    /**
     * 生成时间戳
     * @param int $accuracy 精度 默认微妙
     * @return string
     */
    public static function buildTimestamp($accuracy = 1000)
    {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * $accuracy);
    }

    /**
     * 当前时间多久之前，以天核算
     * @param int $curTime
     * @return false|string
     */
    public static function formatDateLongAgo(int $curTime)
    {
        $todayLast = strtotime(date('Y-m-d 23:59:59'));
        $agoTime = $todayLast - $curTime;
        $agoDay = intval(floor($agoTime / 86400));
        if ($agoDay === 0) {
            return '今天' . date('H:i', $curTime);
        } elseif ($agoDay === 1) {
            return '昨天 ' . date('H:i', $curTime);
        } elseif ($agoDay === 2) {
            return '前天 ' . date('H:i', $curTime);
        } elseif ($agoDay > 2 && $agoDay < 16) {
            return $agoDay . '天前 ' . date('H:i', $curTime);
        } else {
            $format = date('Y') != date('Y', $curTime) ? 'Y-m-d H:i' : 'm-d H:i';
            return date($format, $curTime);
        }
    }

    /**
     * 当前时间多久之前，以分钟到天核算
     * @param int $curTime
     * @return false|string
     */
    public static function formatTimeLongAgo(int $curTime)
    {
        $todayLast = strtotime(date('Y-m-d 23:59:59'));
        $agoTimestamp = time() - $curTime;
        $agoTime = $todayLast - $curTime;
        $agoDay = intval(floor($agoTime / 86400));

        if ($agoTimestamp < 60) {
            $result = '刚刚';
        } elseif ($agoTimestamp < 3600) {
            $result = (ceil($agoTimestamp / 60)) . '分钟前';
        } elseif ($agoTimestamp < 3600 * 12) {
            $result = (ceil($agoTimestamp / 3600)) . '小时前';
        } elseif ($agoDay === 0) {
            $result = '今天 ' . date('H:i', $curTime);
        } elseif ($agoDay === 1) {
            $result = '昨天 ' . date('H:i', $curTime);
        } elseif ($agoDay === 2) {
            $result = '前天 ' . date('H:i', $curTime);
        } elseif ($agoDay > 2 && $agoDay < 16) {
            $result = $agoDay . '天前 ' . date('H:i', $curTime);
        } else {
            $format = date('Y') != date('Y', $curTime) ? 'Y-m-d H:i' : 'm-d H:i';
            return date($format, $curTime);
        }
        return $result;
    }

    /**
     * 每周重复、隔周重复
     * 根据开始日期至结束日期以及指定周几获得对应重复日期
     * @param string $startDate 开始日期 Y-m-d
     * @param string $endDate 结束日期 Y-m-d
     * @param array $week 选中数字周几 [1,2,3,4,5,6,7] 或中文周几 ['周天', '周一', '周二', '周三', '周四', '周五', '周六']
     * @param bool $isApartWeek 是否隔周排期 true | false
     * @param bool $isNumWeek 是否是数字周几 true | false
     * @return array
     */
    public static function generateDateWeek(string $startDate, string $endDate, array $week = [], $isApartWeek = false, $isNumWeek = true)
    {
        $start_date = strtotime($startDate);
        $end_date = strtotime($endDate);
        $days = ($end_date - $start_date) / 86400;
        $weekArr = $isNumWeek ? ['7', '1', '2', '3', '4', '5', '6'] : ['周天', '周一', '周二', '周三', '周四', '周五', '周六'];
        // 组建数组格式 $dataWeek['日期'] => 星期
        $dateWeek = [];
        for ($i = 0; $i < $days; $i++) {
            $num_week = date('w', $start_date + ($i * 86400));
            $dateWeek[date('Y-m-d', $start_date + ($i * 86400))] = $weekArr[$num_week];
        }
        if ($isApartWeek) {
            //以周日为节点，将每周日期规整在一起
            $index = 0;
            $separateDateWeek = [];
            foreach ($dateWeek as $key => $item) {
                $separateDateWeek[$index][] = [$key => $item];
                if ((string)$item == (string)$weekArr[0]) {
                    $index++;
                }
            }
            //对以每周日期规整一起数组取偶，提出隔周日期数据
            $evenDateWeek = [];
            foreach ($separateDateWeek as $key => $item) {
                if (!($key & 1)) {
                    $evenDateWeek = array_merge($evenDateWeek, $item);
                }
            }
            //二维数组合并成一维数组
            $dateWeek = array_reduce($evenDateWeek, 'array_merge', []);
        }
        // 查找两个数组的交集，即获取提交的星期对应的日期
        $newDate = array_intersect($dateWeek, $week);
        // 获取数组中的键值(日期)，并组成一个新数组
        return array_keys($newDate);
    }

    /**
     * 对比两组时间交叉
     * @param int $beginTime1
     * @param int $endTime1
     * @param int $beginTime2
     * @param int $endTime2
     * @return bool
     */
    public static function isDateTimeCross($beginTime1 = 0, $endTime1 = 0, $beginTime2 = 0, $endTime2 = 0)
    {
        $status = $beginTime2 - $beginTime1;

        if ($status > 0) {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 >= 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 指定年月，获取日期，并按每周分组。开始时间周一至周日排序
     * @param $date
     * @param string $format
     * @return array
     * @throws \Exception
     */
    public static function getWeekAMonth($date, $format = 'Y-m-d')
    {
        $time = strtotime($date . '-01');
        $month = date('m', $time);
        $carbon = new Carbon(new Carbon(date('Y-m-d', $time), 'Asia/Shanghai'));
        $weeks = [];
        while (intval($carbon->month) == intval($month)) {
            $weeks[$carbon->weekNumberInMonth][] = $carbon->format($format);
            $carbon->addDay();
        }
        return $weeks;
    }

    /**
     * 指定年月，获取每周起止日期，并按每周分组
     * @param $date
     * @param string $format
     * @return array
     * @throws \Exception
     */
    public static function getWeekStartEndAMoth($date, $format = 'Y-m-d')
    {
        $weeks = self::getWeekAMonth($date, $format);
        $weekData = [];
        foreach ($weeks as $key => $item) {
            $weekData[$key] = [Arr::first($item), Arr::last($item)];
        }
        return $weekData;
    }

    /**
     * 指定日期获取，所在月份周、年、季度
     * @param  $timestamp 时间
     * @param bool $isWeekMonday true ： 一个月以周一开始，为7天；false :一个月以周日开始
     * @return array
     */
    public static function getWeekAndQInAMonth($timestamp, $isWeekMonday = true)
    {
        $dt = Carbon::parse($timestamp);
        $dt->timezone = "Asia/Shanghai";
        return [
            'week' => $isWeekMonday ? $dt->weekNumberInMonth : $dt->weekOfMonth,
            'year' => $dt->year,
            'quarter' => intval(($dt->month - 1) / 3 + 1)
        ];
    }

}
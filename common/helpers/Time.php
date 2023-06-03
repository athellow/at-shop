<?php
/**
 * Time helper
 * @author Athellow
 * @version 1.0
 */
namespace common\helpers;

use Yii;

class Time
{
    /**
     * 获取某个时间戳所属月份的开始和结束日期
     * @param  int|string  $time
     * @return array
     */
    public static function monthDate($time)
    {
        if (empty($time)) {
            return '';
        }

        $subTime = self::getSubTime($time);
        
        return [
            date('Y-m-01', $subTime),
            date('Y-m-t', $subTime)
        ];
    }
   
    /**
     * 格式化时间|支持毫秒级格式化
     * @param  int|string    $time
     * @return string
     */
    public static function formatTime($time, $format = 'Y-m-d H:i:s')
    {
        return empty($time) ? '' : date($format, self::getSubTime($time));
    }

    /**
     * 截取毫秒级时间戳
     * @param  int|string    $time 
     * @return int
     */
    public static function getSubTime($time)
    {
        return empty($time) ? 0 : (int)substr((string)$time, 0, 10);
    }

}
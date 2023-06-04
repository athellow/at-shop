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

    /**
     * 日期时间范围转化时间戳
     * 支持 Y-m-d H:i:s - Y-m-d H:i:s 或 Y-m-d H:i - Y-m-d H:i 或 Y-m-d H - Y-m-d H 或 Y-m-d - Y-m-d 或 Y-m - Y-m 或 Y - Y
     * @param  string $dateRange 日期时间范围
     * @return array
     */
    public static function getRange($dateRange)
    {
        $datetimes = explode(' - ', $dateRange);
        if (count($datetimes) < 2) {
            return [];
        }

        $startDatetime = trim($datetimes[0]);
        $endDatetime = trim($datetimes[1]);

        // 开始
        $startDatetimes = explode(' ', $startDatetime);
        $startDate = trim($startDatetimes[0] ?? '');
        $startDates = explode('-', $startDate);

        $startY = trim($startDates[0] ?? '');       // 开始年份
        $startM = trim($startDates[1] ?? '');       // 开始月份
        $startD = trim($startDates[2] ?? '');       // 开始日期
        $startM = empty($startY) ? '' : (empty($startM) ? '01' : $startM);
        $startD = empty($startM) ? '' : (empty($startD) ? '01' : $startD);

        $startTime = trim($startDatetimes[1] ?? '');
        $startTimes = explode(':', $startTime);
        $startH = trim($startTimes[0] ?? '');       // 开始时
        $startI = trim($startTimes[1] ?? '');       // 开始分
        $startS = trim($startTimes[2] ?? '');       // 开始秒
        $startH = empty($startD) ? '' : (empty($startH) ? '00' : $startH);       // 开始时
        $startI = empty($startH) ? '' : (empty($startI) ? '00' : $startI);       // 开始分
        $startS = empty($startI) ? '' : (empty($startS) ? '00' : $startS);       // 开始秒

        // 结束
        $endDatetimes = explode(' ', $endDatetime);
        $endDate = trim($endDatetimes[0] ?? '');
        $endDates = explode('-', $endDate);

        $endY = trim($endDates[0] ?? '');       // 结束年份
        $endM = trim($endDates[1] ?? '');       // 结束月份
        $endD = trim($endDates[2] ?? '');       // 结束日期
        $endM = empty($endY) ? '' : (empty($endM) ? '12' : $endM);
        $endD = empty($endM) ? '' : (empty($endD) ? date('t', strtotime($endY .'-'. $endY)) : $endD);

        $endTime = trim($endDatetimes[1] ?? '');
        $endTimes = explode(':', $endTime);
        $endH = trim($endTimes[0] ?? '');       // 结束时
        $endI = trim($endTimes[1] ?? '');       // 结束分
        $endS = trim($endTimes[2] ?? '');       // 结束秒
        $endH = empty($endD) ? '' : (empty($endH) ? '23' : $endH);       // 结束时
        $endI = empty($endH) ? '' : (empty($endI) ? '59' : $endI);       // 结束分
        $endS = empty($endI) ? '' : (empty($endS) ? '59' : $endS);       // 结束秒

        
        $starTime = empty($startS) ? 0 : strtotime($startY .'-'. $startM .'-'. $startD .' '. $startH .':'. $startI .':'. $startS);
        $endTime = empty($endS) ? 0 : strtotime($endY .'-'. $endM .'-'. $endD .' '. $endH .':'. $endI .':'. $endS);

        return ['startTime' => $starTime, 'endTime' => $endTime];
    }

}
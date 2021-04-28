<?php

namespace Logic;

class CommonModel
{
    private static $instance = null;
    public static function getInstance()
    {
        if( self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param $date
     * @comment 转化时间
     */
    public function turnTimeForFront($date)
    {
        $dayStartTamp = strtotime(date('Y-m-d')); ## 今天的开始时间戳
        $curYearTamp = strtotime(date('Y').'-01-01 00:00:00'); ## 今年的开始时间戳
        $paramDateTamp = strtotime($date); ## 参数时间的时间戳

        $showStr = '';
        $hourMinute = date('H:i',$paramDateTamp);
        $monthDay = date('m-d', $paramDateTamp);

        if ($paramDateTamp >= $dayStartTamp) {
            $showStr = '今天 '.$hourMinute;
        }

        if ($paramDateTamp < $dayStartTamp && $paramDateTamp >= $curYearTamp) {
            $showStr = $monthDay.' '.$hourMinute;
        }

        if ($paramDateTamp < $curYearTamp) {
            $showStr = date('Y-m-d', $paramDateTamp);
        }

        return $showStr;
    }

    /*
     * @comment 将一些过长的字符串，转化为部分的省略号
     */
    public function turnStrToElipsis($str, $length)
    {
        if (mb_strlen($str, "utf8") > $length) {
            $str = mb_substr($str, 0, $length).'...';
        }
        return $str;
    }
}
<?php

if (!function_exists('img_url')) {
    if (!function_exists('img_url')) {
        function img_url($img = '')
        {
            global $_W;
            if (empty($img)) {
                return "";
            }
            if (substr($img, 0, 6) == 'avatar') {
                return $_W['siteroot'] . "resource/image/avatar/" . $img;
            }
            if (substr($img, 0, 8) == './themes') {
                return $_W['siteroot'] . $img;
            }
            if (substr($img, 0, 1) == '.') {
                return $_W['siteroot'] . substr($img, 2);
            }
            if (substr($img, 0, 5) == 'http:') {
                return $img;
            }
            return $_W['attachurl'] . $img;
        }
    }
}

//使用方法：
//1. 获取当前时间戳(精确到毫秒)：microtime_float()
//2. 时间戳转换时间：microtime_format('Y年m月d日 H时i分s秒 x毫秒', 1270626578.
// 正常的是返回 0.21560000 1498222320

if (!function_exists('microtime_float')) {
    if (!function_exists('microtime_float')) {
        /**
         * 获取当前时间戳，精确到毫秒
         * @return float
         */
        function microtime_float()
        {
            list($usec, $sec) = explode(" ", microtime());
            return ((float)$usec + (float)$sec);
        }
    }
}

if (!function_exists('microtime_format')) {
    if (!function_exists('microtime_format')) {
        /**
         * 格式化时间戳，精确到毫秒，x代表毫秒
         * @param $format
         * @param $_microtime
         *
         * @return mixed
         */
        function microtime_format($format = 'Y年m月d日 H时i分s秒 x毫秒', $_microtime/* 0.21560000 1498222320 */)
        {
            list($usec, $sec) = explode(" ", $_microtime);
            $date = date($format, $sec);
            return str_replace('x', (float)sprintf('%.0f', floatval($usec) *1000), $date);
        }
    }
}

if (!function_exists('getMillisecond')) {
    if (!function_exists('getMillisecond')) {
        /**
         * @return float 1498222320209
         */
        function getMillisecond()
        {
            list($s1, $s2) = explode(' ', microtime());
            return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        }
    }
}


if (!function_exists('getAge')) {
    if (!function_exists('getAge')) {
        /**
         * PHP 年龄计算函数
         *
         * 参数支持数组传参和标准的 Mysql date 类型传参
         * params sample
         * --------------------------------------------------
         * $birthArr = array(
         * 'year' => '2000',
         * 'month' => '11',
         * 'day' => '3'
         * );
         * $birthStr = '2000-11-03';
         * --------------------------------------------------
         * );
         *
         * @param string|array $birthday
         *
         * @return number $age
         */
        function getAge($birthday)
        {
            $age = 0;
            $year = $month = $day = 0;
            if (is_array($birthday)) {
                extract($birthday);
            } else {
                if (strpos($birthday, '-') !== false) {
                    list($year, $month, $day) = explode('-', $birthday);
                    $day = substr($day, 0, 2); //get the first two chars in case of '2000-11-03 12:12:00'
                }
            }
            $age = date('Y') - $year;
            if (date('m') < $month || (date('m') == $month && date('d') < $day)) $age--;
            return $age;
        }
    }
}


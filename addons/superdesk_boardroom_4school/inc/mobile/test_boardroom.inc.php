<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/14/17
 * Time: 3:52 PM
 * @url: http://192.168.1.124/superdesk/app/index.php?i=15&c=entry&do=test_boardroom&m=superdesk_boardroom_4school
 */


global $_GPC, $_W;

$now = strtotime("+30 minutes", time());
$Ymd_start = date('Y-m-d', time());
$Ymd_end = date('Y-m-d', strtotime("+1 week" , time()));

//echo date('Y-m-d H:i:s', time());
//echo "<br/>";
//$i = date('i', time());
//echo $i;
//echo "<br/>";
//echo intval($i);

$curr = time();

$timestamp_start = strtotime("+".(60- intval(date('i', $curr)))." minutes", $curr);
$timestamp_end = strtotime("+30 minutes", $timestamp_start);

$date_start = date('Y-m-d', $timestamp_start);
$time_start = date('H:i', $timestamp_start);
$date_end = date('Y-m-d', $timestamp_end);
$time_end = date('H:i', $timestamp_end);

include $this->template('test_boardroom');
<?php
/**
 * Created by phpstorm.
 * User: LZP
 * Date: 2019/10/8
 * Time: 18:40
 *
 * 定时任务 计算每天的用户评分维度（一天）
 */

error_reporting(0);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';

global $_W;
global $_GPC;

//定时任务 综合维度评分
ignore_user_abort();
set_time_limit(0);

$today = strtotime(date('Y-m-d 00:00:00'));
$yesterday = strtotime('-1 day', time());
$yesterday = date('Y-m-d 00:00:00',$yesterday);
$yesterday = strtotime($yesterday);

 $comment = pdo_fetchall('SELECT c.merchid , AVG (c.logis) l , AVG (c.service) s , AVG (c.describes) d FROM ' . tablename('superdesk_shop_order_comment') . ' c ' . ' WHERE ' . ' c.createtime >= :yesterday ' . ' AND ' . ' c.createtime <= :today ' . ' GROUP BY ' . ' c.merchid ',array(':yesterday'=>$yesterday,':today'=>$today) );

foreach($comment as $k=>$v) {
    $data['merchid'] = $v['merchid'];
    $data['com_logis'] = number_format($v['l']/5*100,2,'.','');
    $data['com_service'] = number_format($v['s']/5*100,2,'.','');
    $data['com_describes'] = number_format($v['d']/5*100,2,'.','');
    $data['compr'] = number_format(($data['com_logis']+$data['com_service']+$data['com_describes'])/3,2,'.','');
    $data['createtime'] = time();
    pdo_insert('superdesk_shop_comments_report',$data);
}


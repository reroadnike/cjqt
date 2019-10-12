<?php
/**
 * Created by phpstorm.
 * User: LZP
 * Date: 2019/10/9
 * Time: 17:51
 */

error_reporting(0);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';

global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$today = strtotime(date('Y-m-d 00:00:00'));
$yesterday = strtotime('-1 day', time());
$yesterday = date('Y-m-d 00:00:00',$yesterday);
$yesterday = strtotime($yesterday);

$goods = pdo_fetchall('SELECT g.merchid , g.orderid , g.goodsid , AVG (g.level) l FROM ' . tablename('superdesk_shop_comments_goods') . ' g ' . ' WHERE ' . ' g.createtime >= :yesterday ' . ' AND ' . ' g.createtime <= :today ' . ' GROUP BY ' . ' g.merchid , g.goodsid ',array(':yesterday'=>$yesterday,':today'=>$today) );

foreach($goods as $v) {
    $data['merchid'] = $v['merchid'];
    $data['orderid'] = $v['orderid'];
    $data['goodsid'] = $v['goodsid'];
    $data['com_level'] = number_format($v['l']/5*100,2,'.','');
    $data['createtime'] = time();
    pdo_insert('superdesk_shop_comments_report_goods',$data);
}

<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_order_insert_enterprise
 */

global $_W;
global $_GPC;

/**
 * 这个sql呢..就是订单表连会员表然后连企业表.然后将企业表中的名称更新到订单表中的企业名称中 为了保险起见...还是从程序上面做...
 * update ims_superdesk_shop_order as o
 * left join ims_superdesk_shop_member as m on m.openid = o.openid
 * left join ims_superdesk_shop_enterprise_user as eu on eu.id = m.core_enterprise
 * set o.member_enterprise_name = eu.enterprise_name where o.member_enterprise_name = ''
 */


die;

$tables = pdo_fetchall('SHOW TABLES like \'%_superdesk_shop_%\'');

foreach ($tables as $k => $v) {

	$v = array_values($v);

	$tablename = str_replace($_W['config']['db']['tablepre'], '', $v[0]);

	if (pdo_fieldexists($tablename, 'openid') && pdo_fieldexists($tablename, 'uniacid')) {
		pdo_delete($tablename, array('openid' => 'wap_user_17_13510109273'));
	}

}

show_json(1,$orderList);
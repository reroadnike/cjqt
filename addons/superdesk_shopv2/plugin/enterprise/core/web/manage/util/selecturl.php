<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';
class Selecturl_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$full = intval($_GPC['full']);
		include $this->template();
	}

	public function querygoods()
	{
		global $_W;
		global $_GPC;
		$type = trim($_GPC['type']);
		$kw = trim($_GPC['kw']);
		$full = intval($_GPC['full']);

		if (!empty($kw) && !empty($type)) {
			if ($type == 'good') {
				$goods = pdo_fetchall('SELECT id,title,productprice,marketprice,thumb,sales,unit FROM ' . tablename('superdesk_shop_goods') . ' WHERE uniacid= :uniacid and enterprise_id=:enterprise_id and status=:status and deleted=0 AND title LIKE :title ', array(':title' => '%' . $kw . '%', ':enterprise_id' => $_W['enterprise_id'], ':uniacid' => $_W['uniacid'], ':status' => '1'));
				$goods = set_medias($goods, 'thumb');
			}
			 else if ($type == 'coupon') {
				$coupons = pdo_fetchall('select id,couponname,coupontype from ' . tablename('superdesk_shop_coupon') . ' where couponname LIKE :title and uniacid=:uniacid and enterprise_id=:enterprise_id', array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $_W['enterprise_id'], ':title' => '%' . $kw . '%'));
			}

		}


		include $this->template('util/selecturl_tpl');
	}
}


?>
<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Recommand_SuperdeskShopV2Page extends WebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$shop = $_W['shopset']['shop'];
			$shop['indexrecommands'] = $_GPC['goodsid'];
			m('common')->updateSysset(array('shop' => $shop));
			plog('shop.recommand', '修改首页推荐商品设置');
			show_json(1);
		}


		$goodsids = ((isset($_W['shopset']['shop']['indexrecommands']) ? implode(',', $_W['shopset']['shop']['indexrecommands']) : ''));
		$goods = false;

		if (!empty($goodsids)) {
			$goods = pdo_fetchall('select id,title,thumb from ' . tablename('superdesk_shop_goods') . ' where id in (' . $goodsids . ') and status=1 and deleted=0 and uniacid=' . $_W['uniacid'] . ' order by instr(\'' . $goodsids . '\',id)');
		}


		$goodsstyle = $_W['shopset']['shop']['goodsstyle'];
		include $this->template();
	}

	public function setstyle()
	{
		global $_W;
		global $_GPC;
		$shop = $_W['shopset']['shop'];
		$shop['goodsstyle'] = intval($_GPC['goodsstyle']);
		m('common')->updateSysset(array('shop' => $shop));
		plog('shop.recommand', '修改手机端商品组样式');
		show_json(1);
	}
}



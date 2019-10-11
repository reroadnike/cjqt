<?php
class Util_SuperdeskShopV2Page extends PluginWebPage 
{
	public function get_menu() 
	{
		global $_W;
		global $_GPC;
		$full = intval($_GPC['full']);
		$syscate = m('common')->getSysset('category');
		if (0 < $syscate['level']) 
		{
			$categorys = pdo_fetchall('SELECT id,name,parentid FROM ' . tablename('superdesk_shop_category') . ' WHERE enabled=:enabled and uniacid= :uniacid  ', array(':uniacid' => $_W['uniacid'], ':enabled' => '1'));
		}
		if (p("diypage")) 
		{
			$diypage = p('diypage')->getPageList('diy')['list'];
			if (!(empty($diypage))) 
			{
				$allpagetype = p('diypage')->getPageType();
			}
		}

		include $this->template();
	}
}
?>
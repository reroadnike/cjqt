<?php
if (!defined('IN_IA')) 
{
	exit('Access Denied');
}
require SUPERDESK_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';
class Withdraw_SuperdeskShopV2Page extends CommissionMobileLoginPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;

		$_W['shopshare']['hideMenus'][] = 'menuItem:share:appMessage';
		$_W['shopshare']['hideMenus'][] = 'menuItem:share:timeline';
		$_W['shopshare']['hideMenus'][] = 'menuItem:share:qq';
		$_W['shopshare']['hideMenus'][] = 'menuItem:share:weiboApp';
		$_W['shopshare']['hideMenus'][] = 'menuItem:share:QZone';
		$_W['shopshare']['hideMenus'][] = 'menuItem:copyUrl';

		$openid = $_W['openid'];
		$core_user = $_W['core_user'];
		$member = $this->model->getInfo($openid, array('total', 'ok', 'apply', 'check', 'lock', 'pay', 'wait', 'fail'), $core_user);
		$cansettle = (1 <= $member['commission_ok']) && (floatval($this->set['withdraw']) <= $member['commission_ok']);
		$agentid = $member['agentid'];
		if (!empty($agentid)) 
		{
			$data = pdo_fetch('select sum(charge) as sumcharge from ' . tablename('superdesk_shop_commission_log') . ' where mid=:mid and uniacid=:uniacid  limit 1', array(':uniacid' => $_W['uniacid'], ':mid' => $agentid));
			$commission_charge = $data['sumcharge'];
			$member['commission_charge'] = $commission_charge;
		}
		else 
		{
			$member['commission_charge'] = 0;
		}
		include $this->template();
	}
}
?>
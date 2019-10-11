<?php

class Login_SuperdeskShopV2Page extends Page
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$set = m('common')->getPluginset('enterprise', $_SESSION['__enterprise_uniacid']);
		$_W['uniacid'] = $_SESSION['__enterprise_uniacid'];

		if ($_W['ispost']) {
			$username = trim($_GPC['username']);
			$pwd = trim($_GPC['pwd']);

			if (empty($username)) {
				show_json(0, '请输入用户名!');
			}


			if (empty($pwd)) {
				show_json(0, '请输入密码!');
			}


			$account = pdo_fetch(
				'select * from ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
				' where uniacid=:uniacid and username=:username limit 1',
				array(
					':uniacid' => $_W['uniacid'],
					':username' => $username
				)
			);


			if (empty($account)) {
				show_json(0, '用户未找到!');
			}


			$pwd = md5($pwd . $account['salt']);

			if ($account['pwd'] != $pwd) {
				show_json(0, '用户密码错误!');
			}


			$user = pdo_fetch('select status from ' . tablename('superdesk_shop_enterprise_user') . ' where uniacid=:uniacid and accountid=:accountid limit 1', array(':uniacid' => $_W['uniacid'], ':accountid' => $account['id']));

			if (!empty($user)) {
				if ($user['status'] == 2) {
					show_json(0, '帐号暂停中,请联系管理员!');
				}

			}


			$account['hash'] = md5($account['pwd'] . $account['salt']);
			$session = base64_encode(json_encode($account));
			$session_key = '__enterprise_' . $account['uniacid'] . '_session';
			isetcookie($session_key, $session, 0, true);
			$status = array();
			$status['lastvisit'] = TIMESTAMP;
			$status['lastip'] = CLIENT_IP;
			pdo_update('superdesk_shop_enterprise_account', $status, array('id' => $account['id']));// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			$url = $_W['siteroot'] . 'web/superdesk_shopv2_enterprise.php?c=site&a=entry&i=' . $account['uniacid'] . '&m=superdesk_shopv2&do=web&r=index';
			show_json(1, array('url' => $url));
		}


		$submitUrl = $_W['siteroot'] . 'web/superdesk_shopv2_enterprise.php?c=site&a=entry&i=' . $_SESSION['__enterprise_uniacid'] . '&m=superdesk_shopv2&do=web&r=login';
		include $this->template('enterprise/manage/login');
	}
}


?>
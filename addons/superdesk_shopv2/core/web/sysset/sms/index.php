<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends ComWebPage
{
	public function __construct($_com = 'sms')
	{
		parent::__construct($_com);
	}

	public function main()
	{
		if (cv('sysset.sms.temp')) {
			header('location: ' . webUrl('sysset/sms/temp'));
		}
		 else if (cv('sysset.sms.set')) {
			header('location: ' . webUrl('sysset/sms/set'));
		}
		 else {
			header('location: ' . webUrl());
		}

		exit();
	}

	public function set()
	{
		global $_W;
		global $_GPC;
		$item = pdo_fetch('SELECT * FROM ' . tablename('superdesk_shop_sms_set') . ' WHERE uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));

		if ($_W['ispost']) {
			$arr = array('juhe' => intval($_GPC['juhe']), 'juhe_key' => trim($_GPC['juhe_key']), 'dayu' => intval($_GPC['dayu']), 'dayu_key' => trim($_GPC['dayu_key']), 'dayu_secret' => trim($_GPC['dayu_secret']), 'emay' => intval($_GPC['emay']), 'emay_url' => trim($_GPC['emay_url']), 'emay_sn' => trim($_GPC['emay_sn']), 'emay_pw' => trim($_GPC['emay_pw']), 'emay_sk' => trim($_GPC['emay_sk']), 'emay_phost' => trim($_GPC['emay_phost']), 'emay_pport' => intval($_GPC['emay_pport']), 'emay_puser' => trim($_GPC['emay_puser']), 'emay_ppw' => trim($_GPC['emay_ppw']), 'emay_out' => intval($_GPC['emay_out']), 'emay_outresp' => (empty($_GPC['emay_outresp']) ? 30 : intval($_GPC['emay_outresp'])), 'emay_warn' => intval($_GPC['emay_warn']), 'emay_mobile' => intval($_GPC['emay_mobile']));

			if (empty($item)) {
				$arr['uniacid'] = $_W['uniacid'];
				pdo_insert('superdesk_shop_sms_set', $arr);
				$id = pdo_insertid();
			}
			 else {
				pdo_update('superdesk_shop_sms_set', $arr, array('id' => $item['id'], 'uniacid' => $_W['uniacid']));
			}

			show_json(1);
		}


		include $this->template();
	}

	public function getnum()
	{
		global $_W;
		global $_GPC;

		if ($_W['ispost']) {
			$result = array();
			$emay_num = com('sms')->sms_num('emay');

			if (!empty($emay_num)) {
				$result['emay'] = $emay_num;
			}


			show_json(1, $result);
		}


		show_json(0);
	}
}



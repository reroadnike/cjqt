<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';
class Index_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		if (mcv('sysset.enterprise')) {
			header('location: ' . enterpriseUrl('sysset/enterprise'));
		}

	}

	public function enterprise()
	{
		global $_W;
		global $_GPC;
		$item = pdo_fetch('select * from ' . tablename('superdesk_shop_enterprise_user') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $_W['uniaccount']['enterprise_id'], ':uniacid' => $_W['uniacid']));
		if (empty($item) || empty($item['accoutntime'])) {
			$accounttime = strtotime('+365 day');
		}
		 else {
			$accounttime = $item['accounttime'];
		}

		if (!empty($item['accountid'])) {
			$account = pdo_fetch(
				'select * from ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
				' where id=:id and uniacid=:uniacid limit 1',
				array(
					':id' => $item['accountid'],
					':uniacid' => $_W['uniacid']
				)
			);

		}


		$diyform_flag = 0;
		$diyform_plugin = p('diyform');
		$f_data = array();

		if ($diyform_plugin) {
			if (!empty($item['diyformdata'])) {
				$diyform_flag = 1;
				$fields = iunserializer($item['diyformfields']);
				$f_data = iunserializer($item['diyformdata']);
			}
			 else {
				$diyform_id = $_W['shopset']['enterprise']['apply_diyformid'];

				if (!empty($diyform_id)) {
					$formInfo = $diyform_plugin->getDiyformInfo($diyform_id);

					if (!empty($formInfo)) {
						$diyform_flag = 1;
						$fields = $formInfo['fields'];
					}

				}

			}
		}


		if ($_W['ispost']) {
			$fdata = array();

			if ($diyform_flag) {
				$fdata = p('diyform')->getPostDatas($fields);

				if (is_error($fdata)) {
					show_json(0, $fdata['message']);
				}

			}


			$data = array(
				'uniacid' => $_W['uniacid'],
				'enterprise_name' => trim($_GPC['enterprise_name']),
				'realname' => trim($_GPC['realname']),
				'mobile' => trim($_GPC['mobile']),
				'desc' => trim($_GPC['desc1']),
				'address' => trim($_GPC['address']),
				'tel' => trim($_GPC['tel']),
				'lng' => $_GPC['map']['lng'],
				'lat' => $_GPC['map']['lat'],
				'logo' => save_media($_GPC['logo'])
			);

			if ($diyform_flag) {
				$data['diyformdata'] = iserializer($fdata);
				$data['diyformfields'] = iserializer($fields);
			}


			pdo_update('superdesk_shop_enterprise_user', $data, array('id' => $_W['uniaccount']['enterprise_id']));
			show_json(1);
		}

		include $this->template('sysset/index');
	}
}


?>
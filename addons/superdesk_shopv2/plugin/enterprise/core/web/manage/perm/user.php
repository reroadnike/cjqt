<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';
class User_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$status = $_GPC['status'];
		$condition = ' and u.uniacid = :uniacid and u.enterprise_id = :enterprise_id and u.isfounder<>1';
		$params = array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $_W['enterprise_id']);

		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' and u.username like :keyword';
			$params[':keyword'] = '%' . $_GPC['keyword'] . '%';
		}


		if ($_GPC['roleid'] != '') {
			$condition .= ' and u.roleid=' . intval($_GPC['roleid']);
		}


		if ($_GPC['status'] != '') {
			$condition .= ' and u.status=' . intval($_GPC['status']);
		}


		$list = pdo_fetchall(
			'SELECT u.*,r.rolename FROM ' . tablename('superdesk_shop_enterprise_account') . ' u  ' . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			' left join ' . tablename('superdesk_shop_enterprise_perm_role') . ' r on u.roleid =r.id  ' .
			' WHERE 1 ' . $condition . ' ORDER BY id desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
			$params
		);

		$total = pdo_fetchcolumn(
			'SELECT count(*) FROM ' . tablename('superdesk_shop_enterprise_account') . ' u  ' . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			' left join ' . tablename('superdesk_shop_enterprise_perm_role') . ' r on u.roleid =r.id  ' .
			' WHERE 1 ' . $condition . ' ',
			$params
		);

		$pager = pagination($total, $pindex, $psize);
		$roles = pdo_fetchall('select id,rolename from ' . tablename('superdesk_shop_enterprise_perm_role') . ' where uniacid=:uniacid and deleted=0', array(':uniacid' => $_W['uniacid']));
		include $this->template();
	}

	public function add()
	{
		$this->post();
	}

	public function edit()
	{
		$this->post();
	}

	protected function post()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$total = $this->model->select_operator();
		($_W['accounttotal'] <= $total) && $this->message('你最多添加' . $_W['accounttotal'] . '个操作员', '', 'error');

		if ($id) {
			$item = pdo_fetch(
				'SELECT * FROM ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
				' WHERE id=:id AND uniacid=:uniacid AND enterprise_id=:enterprise_id',
				array(
					':id' => $id,
					':uniacid' => $_W['uniacid'],
					':enterprise_id' => $_W['enterprise_id']
				)
			);

			$role = pdo_fetch('select id,rolename,enterprise_id from ' . tablename('superdesk_shop_enterprise_perm_role') . ' where uniacid=:uniacid and deleted=0', array(':uniacid' => $_W['uniacid']));
		}


		if ($_W['ispost']) {
			$data = array('username' => trim($_GPC['username']), 'pwd' => trim($_GPC['password']), 'roleid' => trim($_GPC['roleid']), 'status' => trim($_GPC['status']), 'isfounder' => 0, 'uniacid' => $_W['uniacid'], 'enterprise_id' => $_W['enterprise_id']);
			if ($id && !empty($item)) {
				if (empty($data['pwd'])) {
					unset($data['pwd']);
				}
				 else {
					(strlen($data['pwd']) < 6) && show_json(0, '密码至少6位!');
					$data['pwd'] = md5($data['pwd'] . $data['salt']);
				}

				pdo_update('superdesk_shop_enterprise_account', // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
					$data,
					array(
						'id' => $id,
						'uniacid' => $_W['uniacid'],
						'enterprise_id' => $_W['enterprise_id']
					)
				);

				show_json(1);
			}


			($_W['accounttotal'] <= $total) && show_json(0, '你最多添加' . $_W['accounttotal'] . '个操作员');
			(strlen($data['pwd']) < 6) && show_json(0, '密码至少6位!');
			$data['salt'] = random(8);
			$data['pwd'] = md5($data['pwd'] . $data['salt']);
			$is_has = pdo_fetchcolumn(
				'SELECT count(*) FROM ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
				' WHERE username=:username AND uniacid=:uniacid AND enterprise_id=:enterprise_id',
				array(
					':username' => $data['username'],
					':uniacid' => $_W['uniacid'],
					':enterprise_id' => $_W['enterprise_id']
				)
			);


			if ($is_has) {
				show_json(0, '用户名已存在!');
			}


			pdo_insert('superdesk_shop_enterprise_account', $data);// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			show_json(1);
		}


		include $this->template();
	}

	public function delete()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}


		$items = pdo_fetchall(
			'SELECT id,username FROM ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			' WHERE id in( ' . $id . ' ) AND isfounder=0 AND uniacid=' . $_W['uniacid']
		);


		foreach ($items as $item ) {
			pdo_delete('superdesk_shop_enterprise_account', array('id' => $item['id']));// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			mplog('perm.user.delete', '删除操作员 ID: ' . $item['id'] . ' 操作员名称: ' . $item['username'] . ' ');
		}

		show_json(1, array('url' => referer()));
	}

	public function status()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}


		$status = intval($_GPC['status']);
		$items = pdo_fetchall(
			'SELECT id,username FROM ' . tablename('superdesk_shop_enterprise_account') . // TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			' WHERE id in( ' . $id . ' ) AND uniacid=' . $_W['uniacid']
		);


		foreach ($items as $item ) {
			pdo_update('superdesk_shop_enterprise_account', array('status' => $status), array('id' => $item['id']));// TODO 标志 楼宇之窗 openid shop_enterprise_account 不处理
			mplog('perm.user.edit', '修改操作员状态 ID: ' . $item['id'] . ' 操作员名称: ' . $item['username'] . ' 状态: ' . (($status == 0 ? '禁用' : '启用')));
		}

		show_json(1, array('url' => referer()));
	}
}


?>
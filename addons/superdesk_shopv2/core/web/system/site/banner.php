<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

class Banner_SuperdeskShopV2Page extends SystemPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = '';
		$params = array();

		if ($_GPC['status'] != '') {
			$condition .= ' and status=' . intval($_GPC['status']);
		}


		if (!empty($_GPC['keyword'])) {
			$_GPC['keyword'] = trim($_GPC['keyword']);
			$condition .= ' and title like :keyword';
			$params[':keyword'] = '%' . $_GPC['keyword'] . '%';
		}


		$list = pdo_fetchall('SELECT * FROM ' . tablename('superdesk_shop_system_banner') . ' WHERE 1 ' . $condition . '  ORDER BY displayorder DESC limit ' . (($pindex - 1) * $psize) . ',' . $psize, $params);
		$total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('superdesk_shop_system_banner') . ' WHERE 1 ' . $condition, $params);
		$pager = pagination($total, $pindex, $psize);
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

		if ($_W['ispost']) {
			empty($_GPC['title']) && show_json(0, array('message' => '幻灯标题不能为空', 'url' => referer()));
			empty($_GPC['thumb']) && show_json(0, array('message' => '幻灯图片不能为空', 'url' => referer()));
			$data = array('title' => trim($_GPC['title']), 'background' => trim($_GPC['background']), 'thumb' => trim($_GPC['thumb']), 'url' => trim($_GPC['url']), 'displayorder' => intval($_GPC['displayorder']), 'status' => trim($_GPC['status']));

			if (!empty($id)) {
				pdo_update('superdesk_shop_system_banner', $data, array('id' => $id));
				plog('system.site.banner.edit', '修改幻灯片 ID: ' . $id);
			}
			 else {
				$data['createtime'] = TIMESTAMP;
				pdo_insert('superdesk_shop_system_banner', $data);
				$id = pdo_insertid();
				plog('system.site.banner.add', '添加幻灯片 ID: ' . $id);
			}

			show_json(1);
			return NULL;
		}


		$item = pdo_fetch('select * from ' . tablename('superdesk_shop_system_banner') . ' where id=:id limit 1', array(':id' => $id));
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


		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('superdesk_shop_system_banner') . ' WHERE id in( ' . $id . ' )');

		foreach ($items as $item ) {
			pdo_delete('superdesk_shop_system_banner', array('id' => $item['id']));
			plog('system.site.banner.delete', '删除幻灯片 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' ');
		}

		show_json(1, array('url' => referer()));
	}

	public function displayorder()
	{
		global $_W;
		global $_GPC;
		$id = intval($_GPC['id']);
		$displayorder = intval($_GPC['value']);
		$item = pdo_fetchall('SELECT id,title FROM ' . tablename('superdesk_shop_system_banner') . ' WHERE id in( ' . $id . ' )');

		if (!empty($item)) {
			pdo_update('superdesk_shop_system_banner', array('displayorder' => $displayorder), array('id' => $id));
			plog('system.site.banner.delete', '修改幻灯片排序 ID: ' . $item['id'] . ' 标题: ' . $item['title'] . ' 排序: ' . $displayorder . ' ');
		}


		show_json(1);
	}

	public function status()
	{
		global $_GPC;
		$id = intval($_GPC['id']);

		if (empty($id)) {
			$id = ((is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0));
		}


		$items = pdo_fetchall('SELECT id,title FROM ' . tablename('superdesk_shop_system_banner') . ' WHERE id in( ' . $id . ' )');

		foreach ($items as $item ) {
			pdo_update('superdesk_shop_system_banner', array('status' => intval($_GPC['status'])), array('id' => $item['id']));
			plog('system.site.banner.edit', (('修改幻灯片状态<br/>ID: ' . $item['id'] . '<br/>标题: ' . $item['title'] . '<br/>状态: ' . $_GPC['enabled']) == 1 ? '显示' : '隐藏'));
		}

		show_json(1, array('url' => referer()));
	}
}



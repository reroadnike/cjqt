<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';
class Order_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$condition = ' and o.uniacid=:uniacid and o.enterprise_id=:enterprise_id and o.status>=1';
		$params = array(':uniacid' => $_W['uniacid'], ':enterprise_id' => $_W['enterprise_id']);
		if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
			$endtime = time();
		}


		if (!empty($_GPC['datetime']['start']) && !empty($_GPC['datetime']['end'])) {
			$starttime = strtotime($_GPC['datetime']['start']);
			$endtime = strtotime($_GPC['datetime']['end']);
			$condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
			$params[':starttime'] = $starttime;
			$params[':endtime'] = $endtime;
		}


		$searchfield = strtolower(trim($_GPC['searchfield']));
		$_GPC['keyword'] = trim($_GPC['keyword']);

		if (!empty($searchfield) && !empty($_GPC['keyword'])) {
			if ($searchfield == 'ordersn') {
				$condition .= ' and o.ordersn like :keyword';
			}
			 else if ($searchfield == 'member') {
				$condition .= ' and ( m.realname like :keyword or m.mobile like :keyword)';
			}
			 else if ($searchfield == 'address') {
				$condition .= ' and a.realname like :keyword';
			}


			$params[':keyword'] = '%' . $_GPC['keyword'] . '%';
		}


		$condition .= ' and o.deleted = 0 group by o.id';
		$sql =
			' select o.*, a.realname as addressname,m.realname from ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
			' left join ' . tablename('superdesk_shop_member') . ' m on o.openid = m.openid and o.core_user = m.core_user ' .
			' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id = o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
			' where 1 ' . $condition . ' ';

		if (empty($_GPC['export'])) {
			$sql .= 'LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
		}


		$list = pdo_fetchall($sql, $params);

		foreach ($list as &$row ) {
			$row['ordersn'] = $row['ordersn'] . ' ';
			$row['goods'] = pdo_fetchall(
				'SELECT g.thumb,og.price,og.total,og.realprice,g.title,og.optionname ' .
				' from ' . tablename('superdesk_shop_order_goods') . ' og' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
				' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid  ' .
				' where og.uniacid = :uniacid and og.orderid=:orderid order by og.createtime  desc ',
				array(
					':uniacid' => $_W['uniacid'],
					':orderid' => $row['id']
				)
			);

			$totalmoney += $row['price'];
		}

		unset($row);
		$totalcount = $total = pdo_fetchcolumn(
			' select count(o.id) from ' . tablename('superdesk_shop_order') . ' o ' . // TODO 标志 楼宇之窗 openid shop_order 已处理
			' left join ' . tablename('superdesk_shop_member') . ' m on o.openid = m.openid and o.core_user = m.core_user ' .
			' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id = o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
			' where 1 ' . $condition,
			$params
		);

		$totalcount = $total = count($total);
		$pager = pagination($total, $pindex, $psize);

		if ($_GPC['export'] == 1) {
			mca('statistics.order.export');
			$list[] = array('data' => '订单总计', 'count' => $totalcount);
			$list[] = array('data' => '金额总计', 'count' => $totalmoney);

			foreach ($list as &$row ) {
				if ($row['paytype'] == 1) {
					$row['paytype'] = '余额支付';
				}
				 else if ($row['paytype'] == 11) {
					$row['paytype'] = '后台付款';
				}
				 else if ($row['paytype'] == 21) {
					$row['paytype'] = '微信支付';
				}
				 else if ($row['paytype'] == 22) {
					$row['paytype'] = '支付宝支付';
				}
				 else if ($row['paytype'] == 23) {
					$row['paytype'] = '银联支付';
				}
				 else if ($row['paytype'] == 3) {
					$row['paytype'] = '企业月结';
				}


				$row['createtime'] = date('Y-m-d H:i', $row['createtime']);
			}

			unset($row);
			m('excel')->export($list, array(
	'title'   => '订单报告-' . date('Y-m-d-H-i', time()),
	'columns' => array(
		array('title' => '订单号', 'field' => 'ordersn', 'width' => 24),
		array('title' => '总金额', 'field' => 'price', 'width' => 12),
		array('title' => '商品金额', 'field' => 'goodsprice', 'width' => 12),
		array('title' => '运费', 'field' => 'dispatchprice', 'width' => 12),
		array('title' => '付款方式', 'field' => 'paytype', 'width' => 12),
		array('title' => '会员名', 'field' => 'realname', 'width' => 12),
		array('title' => '收货人', 'field' => 'addressname', 'width' => 12),
		array('title' => '下单时间', 'field' => 'createtime', 'width' => 24)
		)
	));
			mplog('statistics.order.export', '导出订单统计');
		}


		load()->func('tpl');
		include $this->template();
	}
}


?>
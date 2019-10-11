<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';
class Index_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		if (mcv('statistics.sale.main')) {
			header('location: ' . enterpriseUrl('statistics/sale'));
			return NULL;
		}


		if (mcv('statistics.sale_analysis.main')) {
			header('location: ' . enterpriseUrl('statistics/sale_analysis'));
			return NULL;
		}


		if (mcv('statistics.order.main')) {
			header('location: ' . enterpriseUrl('statistics/order'));
			return NULL;
		}


		if (mcv('statistics.sale_analysis.main')) {
			header('location: ' . enterpriseUrl('statistics/sale_analysis'));
			return NULL;
		}


		if (mcv('statistics.goods.main')) {
			header('location: ' . enterpriseUrl('statistics/goods'));
			return NULL;
		}


		if (mcv('statistics.goods_rank.main')) {
			header('location: ' . enterpriseUrl('statistics/goods_rank'));
			return NULL;
		}


		if (mcv('statistics.goods_trans.main')) {
			header('location: ' . enterpriseUrl('statistics/goods_trans'));
			return NULL;
		}


		if (mcv('statistics.member_cost.main')) {
			header('location: ' . enterpriseUrl('statistics/member_cost'));
			return NULL;
		}


		if (mcv('statistics.member_increase.main')) {
			header('location: ' . enterpriseUrl('statistics/member_increase'));
			return NULL;
		}


		header('location: ' . enterpriseUrl());
	}
}


?>
<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'enterprise/core/inc/page_enterprise.php';

class Import_SuperdeskShopV2Page extends EnterpriseWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;

		//进来时清空缓存
		cache_delete('enterprise:member_import_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
		cache_delete('enterprise:member_import_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
		$this->cacheImportError(2);

		if ($_W['ispost']) {
			$filename = time() . 'rd' . rand(1000,9999) . 'id' . $_W['uniacid'] . $_W['enterprise_id'];
			$rows = m('excel')->importByPath('excelfile','enterprise','recharge',$filename);

			//2018年9月14日 16:10:32 zjh 可能会算入空白行..判断一下..
			$rows_check = array();
			foreach($rows as $v){
				if(!empty($v[0])){
					$rows_check[] = $v;
				}
			}
			$rows = $rows_check;

			$num = count($rows);
			if($num > 50){
				show_json(0,'最大只能导入50条');
			}

			foreach($rows as $k => &$v){
				$price = number_format(floatval($v[2]),2);
				$v['css'] = false;
				if($price < 0){
					$v['css'] = true;
				}
			}
			unset($v);

			cache_write('enterprise:member_import_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $rows);
			cache_write('enterprise:member_import_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $filename);
			show_json(1,$rows);
		}

		include $this->template();
	}

	/**
	 * 这是适应延迟异步一个个更新的方法
	 */
	public function submitOne() {
		global $_W,$_GPC;

		$type = $_GPC['type'];
		$filename = cache_load('enterprise:member_import_name_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);

		if($type == 1){
			//Excel导入整体数据
			$keys = $_GPC['keys'];
			$rows = cache_load('enterprise:member_import_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
			//$num = count($rows);

			//当遍历更新到最后一个的时候。删除掉cache
//			if($keys >= $num-1){
//				cache_delete('enterprise_member_import');
//				cache_delete('enterprise_member_import_name');
//			}

			$mobile = trim($rows[$keys][0]);
			$realname = trim($rows[$keys][1]);
			$price = round(floatval($rows[$keys][2]),2);
			$nickname = trim($rows[$keys][3]);
		}else if($type == 2){
			//单条数据修改后提交
			$mobile = $_GPC['mobile'];
			$price = $_GPC['price'];
			$error_key = 'e'.$mobile;

			$error = $this->cacheImportError();
			$data = $error[$error_key];

			if(empty($data)){
				show_json(0,'请先通过Excel提交');
			}

			$realname = $data['realname'];
			$nickname = $data['nickname'];
		}else{
			show_json(0,'别乱来！');
		}

		if (empty($mobile)) {
			show_json(0,'手机号不能为空');
		}

		if (0 >= $price) {
			$this->cacheImportError(3,$mobile,
				array(
					'realname' => $realname,
					'nickname' => $nickname
				)
			);

			show_json(0,'金额必须大于0');
		}

		$openid = m('member')->getOpenidByMobileAndEnterprise($mobile,$_W['enterprise_id']);
		$core_user = m('member')->getCoreUserByMobileAndEnterprise($mobile,$_W['enterprise_id']);

		$is_old = 1;

		if (empty($openid)) {

			$openid = m('member')->createMemberByNoWechat($mobile,$realname,$nickname,$_W['enterprise_id']);
			$is_old = 0;
		}

		$old_credit = m('member')->getCredit($openid, 'credit2');

		$member = m('member')->getMember($openid);


		$times = time();

		$rs = m('member')->setCredit($openid,$core_user,
            'credit2',$price,array(),array(
			'type'         => 1,
			'createtime'      => $times,
		));

		if(empty($rs)){
			$this->cacheImportError(3,$mobile,
				array(
					'realname' => $realname,
					'nickname' => $nickname
				)
			);

			show_json(0,'充值失败');
		}

		//插入记录表
		pdo_insert('superdesk_shop_enterprise_import_log',// TODO 标志 楼宇之窗 openid shop_enterprise_import_log 已处理
			array(
				'openid'		  => $openid,
				'core_user'	  => $member['core_user'],
				'uniacid' 		  => $_W['uniacid'],
				'enterprise_id' => $_W['enterprise_id'],
				'realname' 	  => $realname,
				'mobile' 	      => $mobile,
				'old_price'      => $old_credit,
				'price' 	      => $price,
				'nickname' 	  => $nickname,
				'is_old' 	 	  => $is_old,
				'import_sn'	  => $filename,
				'account_id'    => $_W['uniaccount']['id'],
				'createtime'    => $times
			)
		);

		//单条数据修改提交状况
		//充值成功
		//删除该条数据缓存
		if($type == 2){
			$this->cacheImportError(2,$mobile);
		}

		show_json(1);
	}

	/**
	 * 需要导入的数据realname,mobile,nickname,credit2
	 * 需要填写固定值的数据 comeform=mobile,core_enterprise=$_W['enterprise_id'],createtime=time(),mobileverify=1,salt=salt(),pwd=123456
	 */
	public function importTpl()
	{
		$columns = array();
		$columns[] = array('title' => '手机号', 'field' => '', 'width' => 18);
		$columns[] = array('title' => '真实名称', 'field' => '', 'width' => 18);
		$columns[] = array('title' => '充值金额', 'field' => '', 'width' => 18);
		$columns[] = array('title' => '用户昵称', 'field' => '', 'width' => 18);
		m('excel')->temp('批量导入员工数据模板', $columns);
	}

	/**
	 * @param $type		1:获取，2:删除,3:写入
	 * @param $mobile	手机号 作为键
	 * @param array $data  值 目前只有nickname,realname 以数组形式存入cache
	 *
	 */
	private function cacheImportError($type = 1,$mobile = null,$data = array()){
		global $_W;

		$error = cache_load('enterprise:member_import_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
		$error_key = 'e'.$mobile;
		if($type == 1){
			return $error;
		}else if($type == 2){
			if(empty($mobile)){
				cache_delete('enterprise:member_import_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
				return true;
			}

			if(isset($error[$error_key])){
				unset($error[$error_key]);
			}

			if(!empty($error)){
				cache_write('enterprise:member_import_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'],$error);
			}else{
				cache_delete('enterprise:member_import_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id']);
			}
		}else if($type == 3){
			$error = !empty($error) ? $error : array();

			$error[$error_key] = $data;
			cache_write('enterprise:member_import_error_' . $_W['uniacid'] . '_' . $_W['enterprise_id'] . '_' . $_W['uniaccount']['id'], $error);
		}
	}
}


?>
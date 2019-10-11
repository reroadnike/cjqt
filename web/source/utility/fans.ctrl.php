<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('mc');

$do = !empty($_GPC['do']) ? $_GPC['do'] : exit('Access Denied');
$dos = array('browser');
$do = in_array($do, $dos) ? $do: 'browser';

if ($do == 'browser') {
	
	$mode = empty($_GPC['mode']) ? 'visible' : $_GPC['mode'];
	$mode = in_array($mode, array('invisible','visible')) ? $mode : 'visible';
	
	$callback = $_GPC['callback'];
	
	$uids = $_GPC['uids'];
	$uidArr = array();
	if(empty($uids)){
		$uids='';
	}else{
		foreach (explode(',', $uids) as $uid) {
			$uidArr[] = intval($uid);
		}
		$uids = implode(',', $uidArr);
	}
	$where = ' WHERE uniacid = "'.$_W['uniacid'].'" and nickname != "" ';

	if($mode == 'invisible' && !empty($uids)){
		$where .= " AND uid not in ( {$uids} )";
	}
	$params = array();
	if(!empty($_GPC['keyword'])) {
		$where .= ' AND `nickname` LIKE :nickname';
		$params[':nickname'] = "%{$_GPC['keyword']}%";
	}

	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$total = 0;

	$list = pdo_fetchall("SELECT uid, fanid , nickname FROM ".tablename('mc_mapping_fans')." {$where} ORDER BY `fanid` LIMIT ".(($pindex - 1) * $psize).",{$psize}", $params);
	foreach($list as $k =>$v){
		$headimage = mc_fetch($v['uid'],array('avatar'));
		if(!empty($headimage)){
			$list[$k]['avatar'] = $headimage['avatar'];
		}else{
			$list[$k]['avatar'] = 'resource/images/noavatar_middle.gif';
		}
	}
	$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('mc_mapping_fans'). $where , $params);
	$pager = pagination($total, $pindex, $psize, '', array('ajaxcallback'=>'null','mode'=>$mode,'uids'=>$uids));
//	$usergroups = pdo_fetchall('SELECT id, name FROM '.tablename('users_group'), array(), 'id');
	template('utility/fans-browser');
	exit;
}

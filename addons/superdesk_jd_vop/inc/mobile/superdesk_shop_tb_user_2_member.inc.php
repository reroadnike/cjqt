<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/4/17
 * Time: 11:23 AM
 *
 * 一次性任务
 *
 * TODO 要整改成多次增量任务?
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_tb_user_2_member
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=superdesk_shop_tb_user_2_member&toInsert=1
 *
 */
global $_GPC, $_W;

die('禁用');
//include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/task_cron.class.php');
//$task_cron = new task_cronModel();
//
//$_is_ignore = $task_cron->isIgnore(task_cronModel::_cron_handle_task_02_sku_4_page_num);
//
//if ($_is_ignore) {
//    die("ignore task" . PHP_EOL);
//} else {
//    $task_cron->updateLastdo(task_cronModel::_cron_handle_task_02_sku_4_page_num);
//}

$toInsert  = isset($_GPC['toInsert']) ? true : false;    //是否要插入用户表
$status    = isset($_GPC['status']) ? $_GPC['status'] : 1; //(0-未审核;1-已认证;2-未认证)
$isEnabled = isset($_GPC['isEnabled']) ? $_GPC['isEnabled'] : 1;    //1可用 0删除

$page      = $_GPC['page'];
$page      = max(1, intval($page));
$page_size = isset($_GPC['pagesize']) ? $_GPC['pagesize'] : 10000;

$sql       =
    ' SELECT tu.userName,tu.userMobile,tu.virtualArchId,tu.nickName,m.id ' .
    ' FROM ' . tablename('superdesk_core_tb_user') . ' as tu ' .
    '   LEFT JOIN ' . tablename('superdesk_shop_member') . ' as m on tu.userMobile = m.mobile ' .
    ' WHERE m.id is null ' .
    '   AND tu.userName is not null ' .
    '   AND tu.userMobile is not null ' .
    '   AND tu.organizationId is not null ' .
    '   AND tu.virtualArchId is not null ' .
    '   AND tu.status=:status ' .
    '   AND tu.isEnabled=:isEnabled ' .
    ' LIMIT ' . ($page - 1) * $page_size . ',' . $page_size;
$count_sql =
    ' SELECT count(*) as total' .
    ' FROM ' . tablename('superdesk_core_tb_user') . ' as tu ' .
    '   LEFT JOIN ' . tablename('superdesk_shop_member') . ' as m on tu.userMobile = m.mobile ' .
    ' WHERE m.id is null ' .
    '   AND tu.userName is not null ' .
    '   AND tu.userMobile is not null ' .
    '   AND tu.organizationId is not null ' .
    '   AND tu.virtualArchId is not null ' .
    '   AND tu.status=:status ' .
    '   AND tu.isEnabled=:isEnabled ';
$params    = array(':status' => $status, ':isEnabled' => $isEnabled);

$list  = pdo_fetchall($sql, $params);
$count = pdo_fetch($count_sql, $params);

if ($toInsert) {
    $pwd  = "123456";
    $salt = m('account')->getSalt();
    $pwd = md5($pwd . $salt);
    foreach ($list as $k => $v) {
        $openid     = 'wap_user_' . $_W['uniacid'] . '_' . $v['userMobile'];
        $nickname   = !empty($v['nickName']) ? $v['nickName'] : substr($v['userMobile'], 0, 3) . 'xxxx' . substr($v['userMobile'], 7, 4);
        $new_member = array(
            'uniacid'         => $_W['uniacid'],
            'realname'        => $v['userName'],
            'nickname'        => $nickname, // 为了区分这个是导入的fake用户 // $v['nickName'],
            'mobile'          => $v['userMobile'],
            'mobileverify'    => 1,
            'pwd'             => $pwd,
            'salt'            => $salt,
            'openid'          => $openid,
            'core_enterprise' => $v['virtualArchId'],
            'comefrom'        => 'mobile',
            'createtime'      => time(),
        );
        pdo_insert('superdesk_shop_member', $new_member);
    }
}
//json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//print_r($list);
//echo PHP_EOL;
//print_r($count);
//
//$end = microtime(true);
//echo '耗时' . round($end - STARTTIME, 4) . '秒' . PHP_EOL;


include $this->template('superdesk_shop_tb_user_2_member');


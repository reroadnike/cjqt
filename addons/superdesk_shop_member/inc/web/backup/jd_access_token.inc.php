<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=jd_access_token */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/jd_access_token.class.php');
$jd_access_token = new jd_access_tokenModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $jd_access_token->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'UID' => $_GPC['UID'],// 
    'Access_token' => $_GPC['Access_token'],// 
    'Refresh_token' => $_GPC['Refresh_token'],// 
    'time' => $_GPC['time'],// time 为当前时间 expire_in  为 access_token 的过期时间，秒级别，代表 86400 秒后过期，即 24 小时有效期
    'expires_in' => $_GPC['expires_in'],// Access_token 的过期时间，秒级别,有效期 24 小时
    'refresh_token_expires' => $_GPC['refresh_token_expires'],// refresh_token 的过期时间，毫秒级别
    'client_id' => $_GPC['client_id'],// 京东提供的 client_id
    'client_secret' => $_GPC['client_secret'],// 京东提供的 client_secret
    'update_time' => $_GPC['update_time'],// 更新时间

        );
        $jd_access_token->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('jd_access_token', array('op' => 'list')), 'success');


    }
    include $this->template('jd_access_token_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $jd_access_token->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('jd_access_token', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $jd_access_token->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('jd_access_token_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $jd_access_token->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $jd_access_token->delete($id);

    message('删除成功！', referer(), 'success');
}


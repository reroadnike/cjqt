<?php
/**
 *
 * 京东商品池策略管理
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2017/12/18
 * Time: 10:18
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=page_num
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/page_num.class.php');
$page_num = new page_numModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $page_num->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'page_num' => $_GPC['page_num'],// page_num
            'name'     => $_GPC['name'],// 商品池名字
            'state'    => $_GPC['state'],// state

        );
        $page_num->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('page_num', array('op' => 'list')), 'success');


    }
    include $this->template('page_num_edit');

} elseif ($op == 'list') {


    $page      = $_GPC['page'];
    $page_size = 30;

    $result    = $page_num->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('page_num_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $page_num->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $page_num->delete($id);

    message('删除成功！', referer(), 'success');
} elseif ($op == 'state') {

    $state = $_GPC['state'];

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $page_num->getOne($_GPC['id']);

    if (empty($item)) {
        die('抱歉，该信息不存在或是已经被删除！');
    }

    $page_num->update(array(
        'state' => $state
    ), $id);

    die('success');

} elseif ($op == 'deleted') {

    $deleted = $_GPC['deleted'];

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $page_num->getOne($_GPC['id']);

    if (empty($item)) {
        die('抱歉，该信息不存在或是已经被删除！');
    }

    $page_num->update(array(
        'deleted' => $deleted
    ), $id);

    die('success');
}


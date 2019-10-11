<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sll_client_classify */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sll_client_classify.class.php');
$sll_client_classify = new sll_client_classifyModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sll_client_classify->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'client_classify_id' => $_GPC['client_classify_id'],// 
    'client_classify_user_id' => $_GPC['client_classify_user_id'],// 拥有者Id
    'classify_id' => $_GPC['classify_id'],// 对应的总端的分类id
    'client_classify_pid' => $_GPC['client_classify_pid'],// 父类id
    'client_classify_status' => $_GPC['client_classify_status'],// 0：废弃 1：使用 
    'client_classify_ctime' => $_GPC['client_classify_ctime'],// 
    'client_classify_sort' => $_GPC['client_classify_sort'],// 排序

        );
        $sll_client_classify->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sll_client_classify', array('op' => 'list')), 'success');


    }
    include $this->template('sll_client_classify_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sll_client_classify->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sll_client_classify', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sll_client_classify->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sll_client_classify_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sll_client_classify->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sll_client_classify->delete($id);

    message('删除成功！', referer(), 'success');
}


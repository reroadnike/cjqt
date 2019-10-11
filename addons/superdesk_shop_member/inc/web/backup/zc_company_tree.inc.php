<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_company_tree */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_company_tree.class.php');
$zc_company_tree = new zc_company_treeModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_company_tree->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'c_id' => $_GPC['c_id'],// 
    'c_name' => $_GPC['c_name'],// 名称
    'c_pid' => $_GPC['c_pid'],// 父类id
    'c_point' => $_GPC['c_point'],// 节点，1：分公司；2：部门;3：小组;4：职务
    'c_e_id' => $_GPC['c_e_id'],// 企业id
    'c_ctime' => $_GPC['c_ctime'],// 

        );
        $zc_company_tree->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_company_tree', array('op' => 'list')), 'success');


    }
    include $this->template('zc_company_tree_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $zc_company_tree->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_company_tree', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $zc_company_tree->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_company_tree_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_company_tree->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_company_tree->delete($id);

    message('删除成功！', referer(), 'success');
}


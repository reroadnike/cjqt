<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=jd_product_duplicate */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/jd_product_duplicate.class.php');
$jd_product_duplicate = new jd_product_duplicateModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $jd_product_duplicate->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'classify_id' => $_GPC['classify_id'],// 
    'sku' => $_GPC['sku'],// 
    'ctime' => $_GPC['ctime'],// 

        );
        $jd_product_duplicate->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('jd_product_duplicate', array('op' => 'list')), 'success');


    }
    include $this->template('jd_product_duplicate_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $jd_product_duplicate->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('jd_product_duplicate', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $jd_product_duplicate->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('jd_product_duplicate_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $jd_product_duplicate->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $jd_product_duplicate->delete($id);

    message('删除成功！', referer(), 'success');
}


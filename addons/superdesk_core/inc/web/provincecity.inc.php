<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=provincecity */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/provincecity.class.php');
$provincecity = new provincecityModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $provincecity->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'ID' => $_GPC['ID'],
            'type' => $_GPC['type'],
            'name' => $_GPC['name'],
            'provinceCode' => $_GPC['provinceCode'],
            'cityCode' => $_GPC['cityCode'],
            'description' => $_GPC['description'],
            'creator' => $_GPC['creator'],
            'createTime' => $_GPC['createTime'],
            'modifier' => $_GPC['modifier'],
            'modifyTime' => $_GPC['modifyTime'],
            'isEnabled' => $_GPC['isEnabled'],
            'createtime_' => $_GPC['createtime_'],
            'enabled' => $_GPC['enabled'],

        );
        $provincecity->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('provincecity', array('op' => 'list')), 'success');


    }
    include $this->template('provincecity_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $provincecity->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('provincecity', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 10;
    $where = array();
    $where['type'] = 1;


    $result = $provincecity->queryAll($where, $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    foreach ($list as $index => &$_item){

        $item_where = array();
        $item_where['provinceCode'] = $_item['provinceCode'];
        $item_where['type'] = 2;

        $_item['city_items'] = $provincecity->queryAll($item_where);

    }

    unset($_item);

    $pager = pagination($total, $page, $page_size);

    include $this->template('provincecity_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $provincecity->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $provincecity->delete($id);

    message('删除成功！', referer(), 'success');
}

